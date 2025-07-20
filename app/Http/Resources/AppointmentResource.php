<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'date' => $this->resource->date,
            'time' => $this->resource->time,
            'method' => $this->resource->method,
            'status' => $this->resource->status,
            'gmeet_link' => $this->resource->gmeet_link,
            'user' => new UserResource($this->whenLoaded('user')),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'service_type' => $this->resource->service_type,
            'individual_service_type' => $this->resource->individual_service_type,
            'nama' => $this->resource->nama,
            'nik' => $this->resource->nik,
            'npwp' => $this->resource->npwp,
            'efin' => $this->resource->efin,
            'consultant' => new ConsultantResource($this->whenLoaded('consultant')),

            'paymentStatus' => $this->payment->status ?? 'unpaid',
        ];
    }
}

