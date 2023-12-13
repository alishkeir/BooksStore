<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MetadataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'page' => $this->page,
            'data' => [
                'title' => $this->title,
                'description' => $this->description,
            ],
        ];

        return $response;
    }
}
