<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    public function index(IndexBookRequest $request): AnonymousResourceCollection
    {
        $books = $this->bookService->paginate($request->validated());

        return BookResource::collection($books);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->create($request->validated());

        return (new BookResource($book))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Book $book): BookResource
    {
        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $book = $this->bookService->update($book, $request->validated());

        return new BookResource($book);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->delete($book);

        return response()->json(null, 204);
    }
}
