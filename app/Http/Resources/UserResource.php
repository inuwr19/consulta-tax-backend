<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'name'       => $this->resource->name,
            'email'      => $this->resource->email,
            'phone'      => $this->resource->phone_number,
            'role'       => $this->resource->role,
            'created_at' => $this->resource->created_at,
        ];
    }
}

