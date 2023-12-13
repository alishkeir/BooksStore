<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class PostShowResource extends JsonResource
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
            'slug' => $this->slug,
            'lead' => $this->lead,
            'body' => $this->body,
            'cover' => $this->cover,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'published_at' => $this->published_at,
        ];
    }
}
