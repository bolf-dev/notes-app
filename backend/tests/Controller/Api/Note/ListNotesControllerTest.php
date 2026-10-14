<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\Note;

use App\Entity\Note;
use App\Enum\Priority;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ListNotesControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        foreach ([Priority::Low, Priority::High, Priority::Medium, Priority::High] as $i => $priority) {
            $entityManager->persist(new Note("Note $i", 'Content', $priority));
        }
        $entityManager->flush();
    }

    public function testListNotesNewestFirst(): void
    {
        $this->client->request('GET', '/api/notes');

        self::assertResponseIsSuccessful();
        self::assertSame(['Note 3', 'Note 2', 'Note 1', 'Note 0'], $this->getTitles());
    }

    public function testListNotesFilteredByPriority(): void
    {
        $this->client->request('GET', '/api/notes?priority=high');

        self::assertResponseIsSuccessful();
        self::assertSame(['Note 3', 'Note 1'], $this->getTitles());
    }

    public function testListNotesWithInvalidPriority(): void
    {
        $this->client->request('GET', '/api/notes?priority=urgent');

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return list<string>
     */
    private function getTitles(): array
    {
        $body = json_decode((string) $this->client->getResponse()->getContent(), true);

        return array_column($body['data'], 'title');
    }
}
