<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'experience_years' => $this->experience_years,
            'rating' => $this->rating,
            'price_individual_service' => $this->price_individual_service,
            'price_individual_jasa' => $this->price_individual_jasa,
            'price_company_service' => $this->price_company_service,
            'created_at' => $this->created_at,
        ];
    }
}
