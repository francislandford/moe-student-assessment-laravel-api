<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or auth()->user()->can('manage-locations');
    }

    public function rules(): array
    {
        return [
            'county' => ['required', 'string', 'max:150', 'exists:counties,county'],
            'd_name' => ['required', 'string', 'max:200'],
            'date'   => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'county.exists' => 'The selected county does not exist in the system.',
            'd_name.unique' => 'This district name already exists for the selected county.',
        ];
    }
}
