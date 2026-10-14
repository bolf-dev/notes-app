<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Stored as an integer so that notes can be sorted by importance (ORDER BY priority).
 */
enum Priority: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
}
