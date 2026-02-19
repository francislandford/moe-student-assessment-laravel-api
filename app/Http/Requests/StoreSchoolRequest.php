<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or $this->user()->can('create-schools');
    }

    public function rules(): array
    {
        return [
            'county'            => ['nullable', 'string', 'max:100'],
            'district'          => ['nullable', 'string', 'max:200'],
            'school_level'      => ['nullable', 'string', 'max:100'],
            'school_type'       => ['nullable', 'string', 'max:100'],
            'nb_room'       => ['nullable', 'numeric', 'max:100'],
            'school_ownership'  => ['nullable', 'string', 'max:100'],
            'community'         => ['nullable', 'string', 'max:100'],
            'compliance'         => ['nullable'],
            'school_code'       => ['required', 'string', 'max:50', 'unique:schools,school_code'],
            'school_name'       => ['nullable', 'string', 'max:100'],
            'emis_code'       => ['nullable'],
            'tvet'        => ['nullable', 'in:0,1,Y,N,true,false'],
            'accelerated' => ['nullable', 'in:0,1,Y,N,true,false'],
            'alternative' => ['nullable', 'in:0,1,Y,N,true,false'],

            'year_establish' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],

            'permit'        => ['nullable', 'string', 'max:10'],
            'permit_num' => ['nullable', 'string', 'max:30'],

            'principal_name' => ['nullable', 'string', 'max:200'],
            'school_contact' => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:100'],

            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'all_teacher_present' => ['nullable', 'string', 'max:20'],
            'verify_comment'      => ['nullable', 'string'],
            'charge_fees'         => ['nullable', 'string', 'max:30'],
        ];
    }
}
