<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'cover' => $this->cover,
            'list_image_xl' => env('BACKEND_URL').'/storage/'.$this->list_image_xl,
            'list_image_sm' => env('BACKEND_URL').'/storage/'.$this->list_image_sm,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }
}
