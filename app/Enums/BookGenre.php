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

    public const VALUES = [
        self::Fantasy->value,
        self::ScienceFiction->value,
        self::Drama->value,
        self::Mystery->value,
        self::Biography->value,
        self::History->value,
        self::Programming->value,
    ];

    public static function values(): array
    {
        return self::VALUES;
    }
}
