<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\Note;

use App\Entity\Note;
use App\Enum\Priority;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DeleteNoteControllerTest extends WebTestCase
{
    public function testDeleteNote(): void
    {
        $client = static::createClient();

        $note = new Note('Title', 'Content', Priority::Low);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($note);
        $entityManager->flush();
        $id = $note->getId();

        $client->request('DELETE', '/api/notes/'.$id);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        self::assertNull(static::getContainer()->get(NoteRepository::class)->find($id));
    }

    public function testDeleteMissingNoteReturnsNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/notes/999999');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
