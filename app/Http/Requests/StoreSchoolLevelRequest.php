<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or auth()->user()->can('manage-school-levels');
    }

    public function rules(): array
    {
        return [
            'code' => [
                'nullable',
                'string',
                'max:11',
                'unique:school_levels,code',
            ],
            'name' => [
                'nullable',
                'string',
                'max:200',
                'unique:school_levels,name', // prevent duplicate names
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'This level code already exists.',
            'name.unique' => 'This level name already exists.',
        ];
    }
}
