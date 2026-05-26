<?php

namespace Tests\Unit;

use App\Enums\BookGenre;
use PHPUnit\Framework\TestCase;

class BookGenreTest extends TestCase
{
    public function test_values_returns_allowed_book_genres(): void
    {
        $this->assertSame([
            'Fantasy',
            'Science Fiction',
            'Drama',
            'Mystery',
            'Biography',
            'History',
            'Programming',
        ], BookGenre::values());
    }
}
