<?php

namespace App\Http\Requests;

use App\Enums\BookGenre;
use App\Support\BookSort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'genre' => ['sometimes', Rule::enum(BookGenre::class)],
            'search' => ['sometimes', 'string', 'max:255'],
            'sort_by' => ['sometimes', 'string', Rule::in(BookSort::ALLOWED_FIELDS)],
            'sort_direction' => ['sometimes', 'string', Rule::in(BookSort::ALLOWED_DIRECTIONS)],
        ];
    }
}
