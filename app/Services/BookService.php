<?php

namespace App\Services;

use App\Models\Book;
use App\Support\BookSort;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BookService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);
        $sortBy = BookSort::field($filters['sort_by'] ?? null);
        $sortDirection = BookSort::direction($filters['sort_direction'] ?? null);

        return Book::when($filters['genre'] ?? null, function (Builder $query, string $genre): void {
            $query->where('genre', $genre);
        })
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('publisher', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);

        return $book->refresh();
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }
}
