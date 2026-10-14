<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Mapped to HTTP 404 in config/packages/framework.yaml (framework.exceptions).
 */
final class NoteNotFoundException extends \RuntimeException
{
    public static function withId(int $id): self
    {
        return new self(\sprintf('Note with id %d not found.', $id));
    }
}
