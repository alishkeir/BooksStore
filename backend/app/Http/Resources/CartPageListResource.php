<?php

namespace App\Http\Resources;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CartPageListResource extends JsonResource
{
    use ImageTrait;

    public function toArray($request)
    {
        if (isset($this->author)) {
            $authors = $this->author->isNotEmpty() ? implode(', ', $this->author->pluck('title')->toArray()) : null;
        } else {
            $authors = $this->authors ?? null;
        }

        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'state' => $this->state == 1 ? 'preorder' : ((($this->price_sale ?? 0) > 0) ? 'normal' : 'preorder'),
            'type' => $this->type == 0 ? 0 : 1,
            'price_list' => $this->price_list ?? null,
            'price_sale' => $this->price_sale ?? null,
            'price_cart' => $this->price_cart ?? null,
            'discount_percent' => (int) $this->discount_percent ?? null,
            'cover' => $this->cover,
            'selected' => false, // FE fésüli össze a customer objecttel
            'is_new' => (bool) $this->is_new,
            'authors' => $authors,
            'rank' => $this->rank ?? null,
            'published_at' => Carbon::parse($this->published_at)->format(config('pamadmin.date-format')),
        ];
    }
}
