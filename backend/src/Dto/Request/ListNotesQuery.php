<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Enum\Priority;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Query string of GET /api/notes. All filters are optional.
 */
final readonly class ListNotesQuery
{
    public function __construct(
        #[Assert\Choice(callback: [Priority::class, 'labels'])]
        public ?string $priority = null,
    ) {
    }
}
