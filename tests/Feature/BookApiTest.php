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
}
