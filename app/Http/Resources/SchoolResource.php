<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'county'            => $this->county,
            'district'          => $this->district,
            'school_level'      => $this->school_level,
            'school_type'       => $this->school_type,
            'school_ownership'  => $this->school_ownership,
            'community'         => $this->community,
            'school_code'       => $this->school_code,
            'school_name'       => $this->school_name,

            'tvet'        => (bool) $this->tvet,
            'accelerated' => (bool) $this->accelerated,
            'alternative' => (bool) $this->alternative,

            'year_establish' => $this->year_establish,

            'permit'        => $this->permit,
            'permit_num' => $this->permit_num,

            'principal_name' => $this->principal_name,
            'school_contact' => $this->school_contact,
            'email'          => $this->email,

            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,

            'all_teacher_present' => $this->all_teacher_present,
            'verify_comment'      => $this->verify_comment,
            'charge_fees'         => $this->charge_fees,

        ];
    }
}
