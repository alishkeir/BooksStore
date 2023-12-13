<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class CarouselResource extends JsonResource
{
    use ImageTrait;

    public function toArray($request): array
    {
        if ($this->image) {
            $this->image = $this->getOptimizedImage($this->image);
        }

        return [
            'title' => $this->title,
            'cover' => $this->image,
            'url' => $this->url ?? '',
        ];
    }
}
