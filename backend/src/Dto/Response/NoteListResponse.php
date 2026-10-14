<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Note;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

/**
 * List of notes wrapped in "data", so that metadata (e.g. pagination) can be added later
 * without breaking the API contract.
 */
final readonly class NoteListResponse
{
    /**
     * @param list<NoteResponse> $data
     */
    public function __construct(
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: NoteResponse::class)))]
        public array $data,
    ) {
    }

    /**
     * @param list<Note> $notes
     */
    public static function fromEntities(array $notes): self
    {
        return new self(array_map(NoteResponse::fromEntity(...), $notes));
    }
}
