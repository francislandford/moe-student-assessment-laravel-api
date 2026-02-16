<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'username'          => $this->username,
            'usertype'          => $this->usertype,
            'phone'             => $this->phone,
            'project'           => $this->project,
            'cat'               => $this->cat,
            'district'          => $this->district,
            'photo'             => $this->photo,
//            'photo_url'         => $this->photo_url,               // using accessor
            'email_verified_at' => $this->email_verified_at?->toDateTimeString(),
            'created_at'        => $this->created_at?->toDateTimeString(),
            'updated_at'        => $this->updated_at?->toDateTimeString(),

            // Never include password in responses
        ];
    }
}
