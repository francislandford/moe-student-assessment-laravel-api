<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'school' => 'required|string|max:50|exists:schools,school_code', // ← enforces valid school code
            'scores' => 'required|array|min:1',
            'scores.*' => 'nullable|numeric|between:0,2',
        ];
    }

    public function messages(): array
    {
        return [
            'school.required' => 'School code is required.',
            'school.exists'   => 'The selected school code does not exist.',
            'scores.required' => 'At least one score must be provided.',
            'scores.min'      => 'At least one score must be submitted.',
            'scores.*.between' => 'Each score must be 0, 1, or 2.',
        ];
    }
}
