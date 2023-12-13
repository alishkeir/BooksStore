<?php

namespace App\Http\Resources;

use Alomgyar\Products\Product;
use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\Sanctum;

class ProductListResource extends JsonResource
{
    use ImageTrait;

    public $preserveKeys = true;

    public static $wrap = 'products';

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->author)) {
            $authors = $this->author->isNotEmpty() ? implode(', ', $this->author->pluck('title')->toArray()) : null;
        } else {
            $authors = $this->authors ?? null;
        }
        $p = Product::find($this->id); //TODO

        $priceSale = $p->price(request('store'))->price_sale ?? null;
        $priceList = $p->price(request('store'))->price_list ?? null;
        $discountPercent = $p->price(request('store'))->discount_percent ?? null;

        if (request('store') == 2) {
            if ($token = request()->bearerToken()) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
                if (! empty($accessToken)) {
                    $this->customer = $accessToken->tokenable;
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
            }
        }

        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover, '_256');
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'state' => $this->state == 1 ? 'preorder' : ((($p->price(request('store'))->price_sale ?? 0) > 0) ? 'normal' : 'preorder'),
            'type' => $this->type == 0 ? 0 : 1,
            'price_list' => $priceList,
            'price_sale' => $priceSale,
            'price_cart' => $p->price(request('store'))->price_cart ?? null, // hotfix, TODO
            'discount_percent' => $discountPercent,
            'cover' => $this->cover,
            'selected' => false, // FE fésüli össze a customer objecttel
            'is_new' => (bool) $this->is_new,
            'authors' => $authors,
            'rank' => $this->rank ?? null,
            'published_at' => $p->formatted_published_at,
        ];
    }
}
