<?php

namespace App\Http\Resources;

use Alomgyar\Products\Product;
use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;

class CartItemResource extends JsonResource
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
        $priceSale = $this->is_cart_price ? $this->price(request('store'))->price_cart : $this->price(request('store'))->price_sale;
        $priceList = $this->price(request('store'))->price_list;
        $discountPercent = $this->price(request('store'))->discount_percent;

        if (request('store') == 2) {
            if ($token = request()->bearerToken()) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
                if (! empty($accessToken)) {
                    $this->customer = $accessToken->tokenable;
                    if ($this->publisher_id == 38) { //TODO
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
            }
        }

        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'type' => $this->type == 0 ? Product::BOOK : Product::EBOOK,
            'price_list' => $priceList,
            'price_sale' => $priceSale,
            'discount_percent' => $discountPercent,
            'quantity' => $this->quantity,
            'cover' => $this->cover,
            'authors' => $this->author->isNotEmpty() ? AuthorResource::collection($this->author) : [],
            'published_at' => Carbon::parse($this->published_at)->format(config('pamadmin.date-format')),
        ];
    }
}
