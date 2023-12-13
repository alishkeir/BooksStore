<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $primaryAuthor = $this->primaryAuthor->isNotEmpty() ? $this->primaryAuthor->first() : $this->author->first();

        $priceSale = $this->price(request('store'))->price_sale ?? null;
        $priceList = $this->price(request('store'))->price_list ?? null;
        $discountPercent = $this->price(request('store'))->discount_percent ?? null;

        if (request('store') == 2 && ! empty($this->customer)) {
            if (($this->publisher_id ?? false) == 38) { //TODO
                $discount = $this->customer->personal_discount_alomgyar;
            } else {
                $discount = $this->customer->personal_discount_all;
            }
            $priceSalePersonal = round($priceList - (($priceList / 100) * $discount));
            if ($priceSalePersonal < $priceSale) {
                $priceSale = $priceSalePersonal;
                $discountPercent = round(100 - (($priceSale / $priceList) * 100));
            }
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'rating' => [
                'user_rating' => $this->customer->reviews()->where('product.id', $this->id)->first()?->pivot->review,
            ],
            'selected' => $this->customer->isProductSelected($this->id),
            'in_wishlist' => $this->customer->wishlist()->where('product_id', $this->id)->exists(),
            'is_follow_main_author' => isset($primaryAuthor) ? $this->customer->authors()->where('author_id', $primaryAuthor->id)->exists() : false,
            'price_list' => $priceList, // hotfix, TODO
            'price_sale' => $priceSale, // hotfix, TODO
            'discount_percent' => $discountPercent,
        ];
    }
}
