<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Note;
use OpenApi\Attributes as OA;

/**
 * JSON representation of a note returned by the API.
 */
final readonly class NoteResponse
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,

        #[OA\Property(enum: ['low', 'medium', 'high'], example: 'high')]
        public string $priority,

        #[OA\Property(format: 'date-time')]
        public string $createdAt,

        #[OA\Property(format: 'date-time')]
        public string $updatedAt,
    ) {
    }

    public static function fromEntity(Note $note): self
    {
        return new self(
            id: (int) $note->getId(),
            title: $note->getTitle(),
            content: $note->getContent(),
            priority: $note->getPriority()->label(),
            createdAt: $note->getCreatedAt()->format(\DateTimeInterface::ATOM),
            updatedAt: $note->getUpdatedAt()->format(\DateTimeInterface::ATOM),
        );
    }
}
