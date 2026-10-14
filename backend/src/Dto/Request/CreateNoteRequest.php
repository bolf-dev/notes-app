<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Enum\Priority;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Request body of POST /api/notes.
 */
final readonly class CreateNoteRequest
{
    public function __construct(
        #[Assert\NotBlank(normalizer: 'trim')]
        #[Assert\Length(max: 255)]
        public string $title,

        #[Assert\NotBlank(normalizer: 'trim')]
        #[Assert\Length(max: 10000)]
        public string $content,

        #[Assert\NotBlank]
        #[Assert\Choice(callback: [Priority::class, 'labels'])]
        public string $priority,
    ) {
    }
}
