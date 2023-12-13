<?php

namespace App\Http\Resources;

use App\Helpers\StoreHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CartPageResource extends JsonResource
{
    public function toArray($request)
    {
        $onlyFreeDelivery = true;
        $orderOnlyAlone = null;
        foreach ($this->products as $product) {
            if (empty($product->free_delivery)) {
                $onlyFreeDelivery = false;
            }
            if ($product->order_only_alone and count($this->products)>1) {
                $orderOnlyAlone = $product;
            }
        }

        $freeLimit = $onlyFreeDelivery
            ? 0
            : (StoreHelper::freeShippingLimit($this->store) ?? 10000);

        $free = $freeLimit <= $this->total_amount;


        return [
            'id' => $this->id ?? null,
            'total_amount' => $this->total_amount ?? 0,
            'total_amount_full_price' => $this->total_amount_full_price ?? 0,
            'items_in_cart' => $this->items?->sum('quantity'),
            'cart_items' => CartItemResource::collection($this->products),
            'order_only_alone'=>$orderOnlyAlone,
            'free_shipping' => [
                'free' => $free,
                'message' => $free
                    ? StoreHelper::freeShippingLimit($this->store).' Ft felett a szállítás ingyenes'
                    : (-1) * ($this->total_amount - StoreHelper::freeShippingLimit($this->store)).' Ft hiányzik az <strong>ingyenes szállításhoz.</strong>',
                'show' => StoreHelper::showFreeShippingBanner($this->store),
            ],
            'user' => [
                'type' => $this->guest_token ? 'guest' : 'customer',
                'guest_token' => $this->guest_token ?? null,
            ],
        ];
    }
}
