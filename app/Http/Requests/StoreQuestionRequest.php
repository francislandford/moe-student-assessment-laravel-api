<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // or use policy
    }

    public function rules(): array
    {
        return [
            'cat'   => 'required|string|max:100',
            'name'  => 'required|string',
            'order' => 'nullable|integer|min:0',
        ];
    }
}
