<?php

namespace App\Http\Requests;

use App\Enums\BookGenre;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'publisher' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'genre' => ['sometimes', 'required', Rule::enum(BookGenre::class)],
            'publication_date' => 'sometimes|required|date',
            'word_count' => 'sometimes|required|integer|min:1',
            'price_usd' => 'sometimes|required|numeric|decimal:0,2|min:0|max:99999999.99',
        ];
    }
}
