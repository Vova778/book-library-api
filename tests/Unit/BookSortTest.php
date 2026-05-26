<?php

namespace Tests\Unit;

use App\Support\BookSort;
use PHPUnit\Framework\TestCase;

class BookSortTest extends TestCase
{
    public function test_allowed_sort_field_is_returned(): void
    {
        $this->assertSame('price_usd', BookSort::field('price_usd'));
    }

    public function test_invalid_sort_field_falls_back_to_default(): void
    {
        $this->assertSame('created_at', BookSort::field('invalid_field'));
    }

    public function test_null_sort_field_falls_back_to_default(): void
    {
        $this->assertSame('created_at', BookSort::field(null));
    }

    public function test_allowed_sort_direction_is_returned(): void
    {
        $this->assertSame('asc', BookSort::direction('asc'));
    }

    public function test_uppercase_sort_direction_is_normalized(): void
    {
        $this->assertSame('asc', BookSort::direction('ASC'));
    }

    public function test_invalid_sort_direction_falls_back_to_default(): void
    {
        $this->assertSame('desc', BookSort::direction('invalid'));
    }
}
