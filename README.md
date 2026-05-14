# Book Library API

REST API application for managing a simple book library.

The application allows tracking books only, without clients, users, borrowing logic, or other dependencies.

## Tech stack

- PHP 8.5
- Laravel 12
- MySQL 8.4
- Nginx
- Docker Compose
- PHPUnit
- Swagger / OpenAPI

## Features

- List books
- Create a book
- Show a single book
- Update a book
- Delete a book
- Validate API requests
- Seed database with sample books
- Run automated tests
- View Swagger API documentation

## Book model

A book contains the following fields:

| Field | Type | Description |
| --- | --- | --- |
| `title` | string | Book title |
| `publisher` | string | Book publisher |
| `author` | string | Book author |
| `genre` | string | Book genre |
| `publication_date` | date | Book publication date |
| `word_count` | integer | Amount of words in the book |
| `price_usd` | decimal | Book price in US dollars |

## Requirements

- Docker
- Docker Compose

## Setup

Clone the repository:

```bash
git clone https://github.com/Vova778/book-library-api.git
cd book-library-api
```

Create the environment file:

```bash
cp .env.example .env
```

Build and start Docker containers:

```bash
docker compose up -d --build
```

Install Composer dependencies:

```bash
docker compose exec app composer install
```

Generate the application key:

```bash
docker compose exec app php artisan key:generate
```

Run migrations and seed sample books:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Generate Swagger documentation:

```bash
docker compose exec app php artisan l5-swagger:generate
```

The application will be available at:

```text
http://localhost:8080
```

Swagger UI will be available at:

```text
http://localhost:8080/api/documentation
```

## API endpoints

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/books` | List books |
| POST | `/api/books` | Create a book |
| GET | `/api/books/{book}` | Show a book |
| PATCH | `/api/books/{book}` | Update a book |
| DELETE | `/api/books/{book}` | Delete a book |

## Example requests

### List books

```bash
curl http://localhost:8080/api/books \
  -H "Accept: application/json"
```

### Create a book

```bash
curl -X POST http://localhost:8080/api/books \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Clean Code",
    "publisher": "Prentice Hall",
    "author": "Robert C. Martin",
    "genre": "Programming",
    "publication_date": "2008-08-01",
    "word_count": 120000,
    "price_usd": 39.99
  }'
```

### Show a book

```bash
curl http://localhost:8080/api/books/1 \
  -H "Accept: application/json"
```

### Update a book

```bash
curl -X PATCH http://localhost:8080/api/books/1 \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "price_usd": 34.99
  }'
```

### Delete a book

```bash
curl -X DELETE http://localhost:8080/api/books/1 \
  -H "Accept: application/json"
```

## Running tests

Run all tests:

```bash
docker compose exec app php artisan test
```

Run feature tests only:

```bash
docker compose exec app php artisan test --testsuite=Feature
```

## Notes

Generated Swagger JSON files are not committed to the repository.

They can be regenerated with:

```bash
docker compose exec app php artisan l5-swagger:generate
```
