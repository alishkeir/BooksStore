<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this as $options) {
            foreach ($options as $option) {
                $response[$option['section']][$option['key']] = [
                    'key' => $option['key'],
                    'primary' => $option['primary'],
                ];
            }
        }

        return $response;
    }
}
