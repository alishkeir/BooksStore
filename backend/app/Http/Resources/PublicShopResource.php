<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicShopResource extends JsonResource
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
            'address' => $this->title,
            //'address' => $this->zip_code. ' '. ucfirst($this->city). ', ' . ucfirst($this->address),
            // 'title' => $this->title,

            // 'zip_code' => $this->zip_code,
            // 'city' => $this->city,
            // 'address' => $this->address,

            // 'phone' => $this->phone,
            // 'email' => $this->email,
            // 'facebook' => $this->facebook,
            // 'cover' => $this->cover,
            // 'latitude' => $this->latitude,
            // 'longitude' => $this->longitude,
            // 'opening_hours' => $this->opening_hours,
        ];
    }
}
