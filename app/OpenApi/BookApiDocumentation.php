<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Book Library API',
    description: 'REST API documentation for the Book Library application.'
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'Local Docker server'
)]
#[OA\Tag(
    name: 'Books',
    description: 'Book library endpoints'
)]
#[OA\Schema(
    schema: 'Book',
    required: [
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
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Clean Code'),
        new OA\Property(property: 'publisher', type: 'string', example: 'Prentice Hall'),
        new OA\Property(property: 'author', type: 'string', example: 'Robert C. Martin'),
        new OA\Property(property: 'genre', type: 'string', example: 'Programming'),
        new OA\Property(property: 'publication_date', type: 'string', format: 'date', example: '2008-08-01'),
        new OA\Property(property: 'word_count', type: 'integer', example: 120000),
        new OA\Property(property: 'price_usd', type: 'string', example: '39.99'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BookCreatePayload',
    required: [
        'title',
        'publisher',
        'author',
        'genre',
        'publication_date',
        'word_count',
        'price_usd',
    ],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Clean Code'),
        new OA\Property(property: 'publisher', type: 'string', maxLength: 255, example: 'Prentice Hall'),
        new OA\Property(property: 'author', type: 'string', maxLength: 255, example: 'Robert C. Martin'),
        new OA\Property(property: 'genre', type: 'string', maxLength: 255, example: 'Programming'),
        new OA\Property(property: 'publication_date', type: 'string', format: 'date', example: '2008-08-01'),
        new OA\Property(property: 'word_count', type: 'integer', minimum: 1, example: 120000),
        new OA\Property(property: 'price_usd', type: 'number', format: 'float', minimum: 0, example: 39.99),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BookUpdatePayload',
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Updated Book Title'),
        new OA\Property(property: 'publisher', type: 'string', maxLength: 255, example: 'Updated Publisher'),
        new OA\Property(property: 'author', type: 'string', maxLength: 255, example: 'Updated Author'),
        new OA\Property(property: 'genre', type: 'string', maxLength: 255, example: 'Programming'),
        new OA\Property(property: 'publication_date', type: 'string', format: 'date', example: '2008-08-01'),
        new OA\Property(property: 'word_count', type: 'integer', minimum: 1, example: 125000),
        new OA\Property(property: 'price_usd', type: 'number', format: 'float', minimum: 0, example: 34.99),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BookResourceResponse',
    required: ['data'],
    properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Book'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BookCollectionResponse',
    required: [
        'data',
        'links',
        'meta',
    ],
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Book')
        ),
        new OA\Property(property: 'links', type: 'object'),
        new OA\Property(property: 'meta', type: 'object'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    required: [
        'message',
        'errors',
    ],
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(property: 'errors', type: 'object'),
    ],
    type: 'object'
)]
class BookApiDocumentation
{
    #[OA\Get(
        path: '/api/books',
        operationId: 'listBooks',
        summary: 'List books',
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Books list.',
                content: new OA\JsonContent(ref: '#/components/schemas/BookCollectionResponse')
            ),
        ]
    )]
    public function listBooks(): void
    {
    }

    #[OA\Post(
        path: '/api/books',
        operationId: 'createBook',
        summary: 'Create a book',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/BookCreatePayload')
        ),
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Book created.',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResourceResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error.',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function createBook(): void
    {
    }

    #[OA\Get(
        path: '/api/books/{book}',
        operationId: 'showBook',
        summary: 'Show a book',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'book',
                description: 'Book ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Book details.',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResourceResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Book not found.'
            ),
        ]
    )]
    public function showBook(): void
    {
    }

    #[OA\Patch(
        path: '/api/books/{book}',
        operationId: 'updateBook',
        summary: 'Update a book',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/BookUpdatePayload')
        ),
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'book',
                description: 'Book ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Book updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResourceResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Book not found.'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error.',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function updateBook(): void
    {
    }

    #[OA\Delete(
        path: '/api/books/{book}',
        operationId: 'deleteBook',
        summary: 'Delete a book',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'book',
                description: 'Book ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Book deleted.'
            ),
            new OA\Response(
                response: 404,
                description: 'Book not found.'
            ),
        ]
    )]
    public function deleteBook(): void
    {
    }
}
