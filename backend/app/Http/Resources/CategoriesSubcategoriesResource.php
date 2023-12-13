<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesSubcategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subcategories = $this->subcategories->filter(function ($value, $key) use ($request) {
            return $request->subcategories->contains($value);
        });

        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'subcategories' => SubcategoryResource::collection($subcategories),
        ];
    }
}
