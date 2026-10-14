<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\Note;

use App\Entity\Note;
use App\Enum\Priority;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetNoteControllerTest extends WebTestCase
{
    public function testGetNote(): void
    {
        $client = static::createClient();

        $note = new Note('Kúpiť mlieko', '2 litre', Priority::Medium);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($note);
        $entityManager->flush();

        $client->request('GET', '/api/notes/'.$note->getId());

        self::assertResponseIsSuccessful();

        $body = json_decode((string) $client->getResponse()->getContent(), true);
        self::assertSame($note->getId(), $body['id']);
        self::assertSame('Kúpiť mlieko', $body['title']);
        self::assertSame('2 litre', $body['content']);
        self::assertSame('medium', $body['priority']);
    }

    public function testGetMissingNoteReturnsNotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/notes/999999');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
