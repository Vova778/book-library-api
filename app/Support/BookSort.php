<?php

namespace App\Support;

final class BookSort
{
    public const DEFAULT_FIELD = 'created_at';
    public const DEFAULT_DIRECTION = 'desc';

    public const ALLOWED_FIELDS = [
        'created_at',
        'title',
        'publication_date',
        'price_usd',
        'word_count',
    ];

    public const ALLOWED_DIRECTIONS = [
        'asc',
        'desc',
    ];

    public static function field(?string $field): string
    {
        if ($field === null) {
            return self::DEFAULT_FIELD;
        }

        if (!in_array($field, self::ALLOWED_FIELDS, true)) {
            return self::DEFAULT_FIELD;
        }

        return $field;
    }

    public static function direction(?string $direction): string
    {
        $direction = strtolower($direction ?? '');

        if (!in_array($direction, self::ALLOWED_DIRECTIONS, true)) {
            return self::DEFAULT_DIRECTION;
        }

        return $direction;
    }
}
