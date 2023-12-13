<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickUpPointResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'provider' => $this->provider,
            'provider_name' => $this->provider_name,
            'provider_id' => $this->provider_id,
            'provider_type' => $this->provider_type,
            'name' => $this->name,
            'lat' => floatval($this->lat),
            'lng' => floatval($this->lng),
            'zip' => $this->zip,
            'city' => $this->city,
            'address' => $this->address,
        ];
    }
}
