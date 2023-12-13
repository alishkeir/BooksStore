<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
{
    use ImageTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'product_cover' => $this->cover,
            'product_title' => $this->product->title,
            'product_slug' => $this->product->slug,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'authors' => AuthorResource::collection($this->product->author),
        ];
    }
}
