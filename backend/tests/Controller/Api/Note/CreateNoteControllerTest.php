<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\Note;

use App\Enum\Priority;
use App\Repository\NoteRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateNoteControllerTest extends WebTestCase
{
    public function testCreateNote(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/notes', [
            'title' => '  Kúpiť mlieko  ',
            'content' => '2 litre polotučného',
            'priority' => 'high',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $body = json_decode((string) $client->getResponse()->getContent(), true);
        self::assertSame('Kúpiť mlieko', $body['title']);
        self::assertSame('2 litre polotučného', $body['content']);
        self::assertSame('high', $body['priority']);

        $note = static::getContainer()->get(NoteRepository::class)->find($body['id']);
        self::assertNotNull($note);
        self::assertSame(Priority::High, $note->getPriority());
    }

    /**
     * @param array<string, mixed> $payload
     * @param list<string>         $invalidFields
     */
    #[DataProvider('invalidPayloadProvider')]
    public function testCreateNoteWithInvalidPayload(array $payload, array $invalidFields): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/notes', $payload);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $body = json_decode((string) $client->getResponse()->getContent(), true);
        self::assertEqualsCanonicalizing($invalidFields, array_column($body['violations'], 'propertyPath'));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, list<string>}>
     */
    public static function invalidPayloadProvider(): iterable
    {
        $valid = ['title' => 'Title', 'content' => 'Content', 'priority' => 'medium'];

        yield 'missing fields' => [[], ['title', 'content', 'priority']];
        yield 'title too long' => [['title' => str_repeat('a', 256)] + $valid, ['title']];
        yield 'content too long' => [['content' => str_repeat('a', 10001)] + $valid, ['content']];
        yield 'unknown priority' => [['priority' => 'urgent'] + $valid, ['priority']];
    }
}
