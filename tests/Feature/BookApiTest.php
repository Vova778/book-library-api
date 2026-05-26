<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_can_be_listed(): void
    {
        Book::factory()
            ->count(3)
            ->create();

        $response = $this->getJson('/api/books');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'publisher',
                        'author',
                        'genre',
                        'publication_date',
                        'word_count',
                        'price_usd',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_books_can_be_paginated_with_custom_page_size(): void
    {
        Book::factory()
            ->count(5)
            ->create();

        $response = $this->getJson('/api/books?per_page=2');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 5);
    }

    public function test_book_can_be_created(): void
    {
        $payload = [
            'title' => 'Clean Code',
            'publisher' => 'Prentice Hall',
            'author' => 'Robert C. Martin',
            'genre' => 'Programming',
            'publication_date' => '2008-08-01',
            'word_count' => 120000,
            'price_usd' => 39.99,
        ];

        $response = $this->postJson('/api/books', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.publisher', $payload['publisher'])
            ->assertJsonPath('data.author', $payload['author'])
            ->assertJsonPath('data.genre', $payload['genre'])
            ->assertJsonPath('data.publication_date', $payload['publication_date'])
            ->assertJsonPath('data.word_count', $payload['word_count'])
            ->assertJsonPath('data.price_usd', '39.99');

        $this->assertDatabaseHas('books', [
            'title' => $payload['title'],
            'publisher' => $payload['publisher'],
            'author' => $payload['author'],
            'genre' => $payload['genre'],
            'word_count' => $payload['word_count'],
            'price_usd' => $payload['price_usd'],
        ]);
    }

    public function test_book_can_be_shown(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonPath('data.title', $book->title)
            ->assertJsonPath('data.publisher', $book->publisher)
            ->assertJsonPath('data.author', $book->author);
    }

    public function test_missing_book_returns_not_found_response(): void
    {
        $response = $this->getJson('/api/books/999');

        $response
            ->assertNotFound()
            ->assertJson([
                'message' => 'Book not found.',
            ]);
    }

    public function test_book_can_be_updated(): void
    {
        $book = Book::factory()->create();

        $payload = [
            'title' => 'Updated Book Title',
            'price_usd' => 49.99,
        ];

        $response = $this->patchJson("/api/books/{$book->id}", $payload);

        $response
            ->assertOk()
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.price_usd', '49.99');

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => $payload['title'],
            'price_usd' => $payload['price_usd'],
        ]);
    }

    public function test_book_can_be_deleted(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_book_creation_requires_valid_data(): void
    {
        $response = $this->postJson('/api/books', [
            'title' => '',
            'publisher' => '',
            'author' => '',
            'genre' => '',
            'publication_date' => 'invalid-date',
            'word_count' => 0,
            'price_usd' => 10.999,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'title',
                'publisher',
                'author',
                'genre',
                'publication_date',
                'word_count',
                'price_usd',
            ]);
    }

    public function test_book_update_accepts_partial_data(): void
    {
        $book = Book::factory()->create([
            'title' => 'Original Title',
            'price_usd' => 19.99,
        ]);

        $response = $this->patchJson("/api/books/{$book->id}", [
            'price_usd' => 24.99,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.title', 'Original Title')
            ->assertJsonPath('data.price_usd', '24.99');

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Original Title',
            'price_usd' => 24.99,
        ]);
    }

    public function test_books_can_be_filtered_by_genre(): void
    {
        Book::factory()->create([
            'title' => 'Fantasy Book',
            'genre' => 'Fantasy',
        ]);

        Book::factory()->create([
            'title' => 'Drama Book',
            'genre' => 'Drama',
        ]);

        $response = $this->getJson('/api/books?genre=Fantasy');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Fantasy Book')
            ->assertJsonPath('data.0.genre', 'Fantasy');
    }

    public function test_books_can_be_searched_by_title(): void
    {
        Book::factory()->create([
            'title' => 'Clean Architecture',
        ]);

        Book::factory()->create([
            'title' => 'Domain-Driven Design',
        ]);

        $response = $this->getJson('/api/books?search=Clean');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Clean Architecture');
    }

    public function test_books_can_be_sorted_by_price(): void
    {
        Book::factory()->create([
            'title' => 'Expensive Book',
            'price_usd' => 99.99,
        ]);

        Book::factory()->create([
            'title' => 'Cheap Book',
            'price_usd' => 9.99,
        ]);

        $response = $this->getJson('/api/books?sort_by=price_usd&sort_direction=asc');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Cheap Book')
            ->assertJsonPath('data.1.title', 'Expensive Book');
    }

    public function test_book_creation_rejects_invalid_genre(): void
    {
        $payload = [
            'title' => 'Invalid Genre Book',
            'publisher' => 'Test Publisher',
            'author' => 'Test Author',
            'genre' => 'Invalid Genre',
            'publication_date' => '2024-01-01',
            'word_count' => 50000,
            'price_usd' => 19.99,
        ];

        $response = $this->postJson('/api/books', $payload);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['genre']);
    }
}
