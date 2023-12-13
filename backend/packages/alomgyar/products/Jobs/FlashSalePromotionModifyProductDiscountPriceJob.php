<?php

namespace Alomgyar\Products\Jobs;

use Alomgyar\Products\ProductPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlashSalePromotionModifyProductDiscountPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ProductPrice $productPrice, public $targetDiscountPercentage, public $salePrice)
    {
        // --
    }

    public function handle()
    {
        $this->productPrice->discount_percent = $this->targetDiscountPercentage;
        $this->productPrice->price_sale = $this->salePrice;
        $this->productPrice->price_sale_original = $this->salePrice;
        $this->productPrice->save();
    }
}
