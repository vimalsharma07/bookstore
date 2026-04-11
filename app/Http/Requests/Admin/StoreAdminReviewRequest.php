<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string', 'max:5000'],
            'reviewer_name' => ['required', 'string', 'max:120'],
            'is_approved' => ['required', Rule::in([0, 1, '0', '1'])],
        ];
    }
}
