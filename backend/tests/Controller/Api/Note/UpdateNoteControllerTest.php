<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\Note;

use App\Entity\Note;
use App\Enum\Priority;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UpdateNoteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private Note $note;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->note = new Note('Old title', 'Old content', Priority::Low);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($this->note);
        $entityManager->flush();
    }

    public function testUpdateOnlyProvidedFields(): void
    {
        $this->client->jsonRequest('PATCH', '/api/notes/'.$this->note->getId(), [
            'title' => 'New title',
            'priority' => 'high',
        ]);

        self::assertResponseIsSuccessful();

        $body = json_decode((string) $this->client->getResponse()->getContent(), true);
        self::assertSame('New title', $body['title']);
        self::assertSame('Old content', $body['content']);
        self::assertSame('high', $body['priority']);

        $note = static::getContainer()->get(NoteRepository::class)->find($this->note->getId());
        self::assertSame('New title', $note?->getTitle());
        self::assertSame(Priority::High, $note?->getPriority());
    }

    public function testUpdateMissingNoteReturnsNotFound(): void
    {
        $this->client->jsonRequest('PATCH', '/api/notes/999999', ['title' => 'New title']);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateWithInvalidValueReturnsValidationError(): void
    {
        $this->client->jsonRequest('PATCH', '/api/notes/'.$this->note->getId(), ['title' => '']);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
