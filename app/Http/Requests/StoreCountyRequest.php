<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or auth()->user()->can('manage-locations');
    }

    public function rules(): array
    {
        return [
            'county' => [
                'required',
                'string',
                'max:100',
                'unique:counties,county',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'county.unique' => 'This county already exists in the system.',
        ];
    }
}
