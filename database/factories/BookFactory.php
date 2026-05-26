<?php

namespace Database\Factories;

use App\Enums\BookGenre;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'publisher' => fake()->company(),
            'author' => fake()->name(),
            'genre' => fake()->randomElement(BookGenre::values()),
            'publication_date' => fake()->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),
            'word_count' => fake()->numberBetween(30000, 250000),
            'price_usd' => fake()->randomFloat(2, 5, 150),
        ];
    }
}
