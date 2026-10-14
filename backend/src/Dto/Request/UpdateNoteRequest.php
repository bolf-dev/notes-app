<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Enum\Priority;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Request body of PATCH /api/notes/{id}.
 *
 * Every field is optional: a missing (or null) field keeps its current value,
 * a provided field must satisfy the same rules as on create.
 */
final readonly class UpdateNoteRequest
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
        #[Assert\Length(max: 255)]
        public ?string $title = null,

        #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
        #[Assert\Length(max: 10000)]
        public ?string $content = null,

        #[Assert\Choice(callback: [Priority::class, 'labels'])]
        public ?string $priority = null,
    ) {
    }
}
