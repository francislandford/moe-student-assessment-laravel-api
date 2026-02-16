<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomObservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'school'       => 'required|string|max:50|exists:schools,school_code',
            'class_num'    => 'required|integer|between:1,3', // only 1,2,3 allowed
            'grade'        => 'nullable|string|max:100',
            'subject'      => 'nullable|string|max:100',
            'teacher'      => 'nullable|string|max:150',
            'nb_male'      => 'required|numeric|max:100',
            'nb_female'     => 'required|numeric|max:100',
            'scores'       => 'required|array|min:1',
            'scores.*'     => 'nullable|numeric|between:0,1', // Yes=1, No=0
        ];
    }

    public function messages(): array
    {
        return [
            'school.required'     => 'School code is required.',
            'school.exists'       => 'The selected school code does not exist.',
            'class_num.between'   => 'Class number must be 1, 2, or 3.',
            'scores.required'     => 'At least one score must be provided.',
            'nb_male.required'     => 'Number of males must be provided.',
            'nb_female.required'     => 'Number of females must be provided.',
            'scores.*.between'    => 'Each score must be 0 (No) or 1 (Yes).',
        ];
    }
}
