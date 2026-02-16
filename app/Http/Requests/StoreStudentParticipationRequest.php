<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentParticipationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or add proper authorization
    }

    public function rules(): array
    {
        return [
            'school'        => 'required|string|max:255',
            'scores'        => 'required|array',
            'scores.*'      => 'nullable|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'school.required' => 'School code is required.',
            'scores.required' => 'Scores data is required.',
            'scores.*.in'     => 'Each score must be 0 or 1.',
        ];
    }
}
