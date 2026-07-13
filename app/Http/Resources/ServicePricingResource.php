<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePricingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'service_id' => $this->service_id,
            'vehicle_category' => new VehicleCategoryResource($this->whenLoaded('vehicleCategory')),
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes
        ];
    }
}
