<?php

namespace Alomgyar\Products\Listeners;

use Alomgyar\Products\Events\ProductOrderableEvent;
use Alomgyar\Products\Jobs\ProductOrderableJob;
use Alomgyar\Products\Jobs\ProductOrderablePublicJob;
use App\PreOrder;
use App\PublicPreOrder;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductOrderableListener implements ShouldQueue
{
    public function handle(ProductOrderableEvent $event)
    {
        $product = $event->getProduct();

        $items = PreOrder::where('product_id', $product->id)
            ->whereNull('notified_at')
            ->get();

        foreach ($items as $item) {
            if (! $item->notified_at) {
                dispatch(new ProductOrderableJob($product, $item->customer));

                $item->notified_at = Carbon::now();
                $item->save();
            }
        }

        $publicItems = PublicPreOrder::where('product_id', $product->id)
            ->whereNull('notified_at')
            ->get();

        foreach ($publicItems as $publicItem) {
            if (! $publicItem->notified_at) {
                dispatch(new ProductOrderablePublicJob($product, $publicItem->email, $publicItem->store));

                $publicItem->notified_at = Carbon::now();
                $publicItem->save();
            }
        }
    }
}
