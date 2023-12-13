<?php

namespace App\Http\Resources;

use Alomgyar\Products\Product;
use App\Http\Traits\ImageTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use JsonSerializable;

class ReviewsResource extends JsonResource
{
    use ImageTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'state' => $this->state == 1 ? 'preorder' : 'normal',
            'type' => $this->type == 0 ? Product::BOOK : Product::EBOOK,
            'price_list' => $this->prices?->price_list,
            'price_sale' => $this->prices?->price_sale,
            'discount_percent' => $this->prices?->discount_percent,
            'cover' => $this->cover,
            'authors' => $this->author->isNotEmpty() ? AuthorResource::collection($this->author) : [],
            'rank' => $this->rank ?? null,
            'review' => $this->pivot->review,
            'review_date' => Carbon::parse($this->review_date)->format(config('pamadmin.date-format')),
        ];
    }
}
