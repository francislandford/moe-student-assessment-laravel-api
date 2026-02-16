<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'school' => 'required|string|max:50|exists:schools,school_code',
            'scores' => 'required|array|min:1',
            'scores.*' => 'nullable|numeric|between:0,2', // 0=No, 1=Yes (1pt), 2=Yes (2pt)
        ];
    }

    public function messages(): array
    {
        return [
            'school.required' => 'School code is required.',
            'school.exists'   => 'The selected school code does not exist.',
            'scores.required' => 'At least one score must be provided.',
            'scores.*.between' => 'Each score must be 0, 1, or 2.',
        ];
    }
}
