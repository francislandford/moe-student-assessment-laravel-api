<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolOwnershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or auth()->user()->can('manage-ownership-types');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
                'unique:school_ownerships,name',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This ownership type already exists.',
        ];
    }
}
