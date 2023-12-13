<?php

namespace Alomgyar\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class PickupPointSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->provider_id,
            'text' => $this->provider_name. ' '. $this->name
        ];
    }
}
