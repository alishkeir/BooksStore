<?php

namespace Alomgyar\Products\Services;

use Alomgyar\Products\Jobs\ProductHasNormalStateMailJob;
use Alomgyar\Products\Product;
use App\WishItem;

class ProductHasNormalStateService
{
    public function sendEmails(Product $product): void
    {
        $wishItems = WishItem::where('product_id', $product->id)
            ->whereNull('notified_at')
            ->get();

        foreach ($wishItems as $wishItem) {
            dispatch(new ProductHasNormalStateMailJob($wishItem));
        }
    }
}
