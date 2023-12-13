<?php

namespace Alomgyar\Products\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class FlashSalePromotionAddProductToPromotionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $storeNumber, public $productId, public $promotionId, public $salePrice)
    {
        // --
    }

    public function handle()
    {
        $promotionProduct = [
            'promotion_id' => $this->promotionId,
            'product_id' => $this->productId,
            'price_sale_0' => $this->storeNumber == 0 ? $this->salePrice : 0,
            'price_sale_1' => $this->storeNumber == 1 ? $this->salePrice : 0,
            'price_sale_2' => $this->storeNumber == 2 ? $this->salePrice : 0,
        ];

        DB::table('promotion_product')->insert($promotionProduct);
    }
}
