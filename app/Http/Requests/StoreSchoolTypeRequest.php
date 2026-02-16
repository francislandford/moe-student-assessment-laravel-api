<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or auth()->user()->can('manage-school-types');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
                'unique:school_types,name', // prevent duplicates
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This school type already exists.',
        ];
    }
}
