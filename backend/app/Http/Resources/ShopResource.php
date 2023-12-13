<?php

namespace App\Http\Resources;

use App\Helpers\StoreHelper;
use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    use ImageTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'facebook' => $this->facebook,
            //'cover' => StoreHelper::currentStore() . $this->cover,
            'cover' => $this->cover,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'opening_hours' => $this->opening_hours,
            'show_shipping' => $this->show_shipping
        ];
    }
}
