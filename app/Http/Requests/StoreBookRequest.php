<?php

namespace App\Http\Requests;

use App\Enums\BookGenre;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => ['required', Rule::enum(BookGenre::class)],
            'publication_date' => 'required|date',
            'word_count' => 'required|integer|min:1',
            'price_usd' => 'required|numeric|decimal:0,2|min:0|max:99999999.99',
        ];
    }
}
