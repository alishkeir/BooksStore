<?php

namespace Alomgyar\Products\Listeners;

use Alomgyar\Products\Events\ProductStateChangedEvent;
use Alomgyar\Products\Product;
use Alomgyar\Products\Services\ProductHasNormalStateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ToggleProductStateListener implements ShouldQueue
{
    private $products;

    public function __construct()
    {
    }

    public function handle(ProductStateChangedEvent $event)
    {
        $productIDs = collect($event->inventory)->pluck('product_id');
        $this->products = DB::table('product')
                            ->where('is_dependable_status', 1)
                            ->where('type', Product::BOOK)
                            ->whereIn('id', $productIDs)
                            ->get();

        foreach ($this->products as $product) {
            if ($product->state === Product::STATE_NORMAL and Product::inventoryLessThanOrEqual($product->id, 0) and empty($product->book24_stock)) { // és nincs a book24-en sem
                DB::table('product')->where('id', $product->id)->update(['state' => Product::STATE_PRE]);
                Log::info($product->id.' id-jú termék state átállítva: '.Product::STATE_PRE);
            }

            if ($product->state === Product::STATE_PRE and (Product::inventoryGreaterThanZero($product->id) or $product->book24_stock > 0)) { // vagy van a book24-en
                DB::table('product')->where('id', $product->id)->update(['state' => Product::STATE_NORMAL,
                    //'published_at' => now()
                ]);
                Log::info($product->id.' id-jú termék state átállítva: '.Product::STATE_NORMAL);

                //(new ProductHasNormalStateService())->sendEmails(Product::find($product->id));
            }
        }
    }
}
