<?php

namespace App\Http\Resources;

use Alomgyar\Countries\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'last_name' => $this->last_name ?? null,
            'first_name' => $this->first_name ?? null,
            'business_name' => $this->business_name ?? null,
            'vat_number' => $this->vat_number ?? null,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'address' => $this->address,
            'country' => new CountryResource(Country::find($this->country_id)),
            'comment' => $this->comment,
            'type' => $this->type,
            'entity_type' => $this->entity,
        ];
    }
}
