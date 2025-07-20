<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'method'           => $this->method,
            'reference_number' => $this->reference_number,
            'proof_url'        => $this->proof_url,
            'status'           => $this->status,
            'paid_at'          => $this->paid_at,
            'snap_token'       => $this->snap_token,
            'user'             => new UserResource($this->whenLoaded('user')),
            'appointment'      => new AppointmentResource($this->whenLoaded('appointment')),
        ];
    }
}

