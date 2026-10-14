<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Stored as an integer so that notes can be sorted by importance (ORDER BY priority).
 * The API exposes the lowercase label ("low", "medium", "high") instead of the number.
 */
enum Priority: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;

    public static function fromLabel(string $label): self
    {
        foreach (self::cases() as $priority) {
            if ($priority->label() === $label) {
                return $priority;
            }
        }

        throw new \ValueError(\sprintf('"%s" is not a valid priority label.', $label));
    }

    /**
     * @return list<string>
     */
    public static function labels(): array
    {
        return array_map(static fn (self $priority): string => $priority->label(), self::cases());
    }

    public function label(): string
    {
        return strtolower($this->name);
    }
}
