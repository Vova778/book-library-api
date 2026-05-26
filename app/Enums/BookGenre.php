<?php

namespace App\Enums;

enum BookGenre: string
{
    case Fantasy = 'Fantasy';
    case ScienceFiction = 'Science Fiction';
    case Drama = 'Drama';
    case Mystery = 'Mystery';
    case Biography = 'Biography';
    case History = 'History';
    case Programming = 'Programming';

    public static function values(): array
    {
        return array_map(
            static fn(self $genre): string => $genre->value,
            self::cases()
        );
    }
}
