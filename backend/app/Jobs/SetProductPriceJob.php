<?php

namespace App\Jobs;

use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetProductPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $updateBook;

    public $listPrice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $updateBook, $listPrice)
    {
        $this->updateBook = $updateBook;
        $this->listPrice = $listPrice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->updateBook->do_not_update_price) {
            return;
        }
        if($this->updateBook->discount_type === Product::DISCOUNT_TYPE_NEW_RATE){
            $isNewDiscount = true;
        }else if($this->updateBook->discount_type === Product::DISCOUNT_TYPE_DEFAULT_RATE){
            $isNewDiscount = false;
        }else{
            $isNewDiscount = strtotime($this->updateBook->published_at) > time() - 345600;
        }

        $stores = [
            0 => 'default_discount_rate_alomgyar',
            1 => 'default_discount_rate_olcsokonyvek',
            2 => 'default_discount_rate_nagyker',
        ];
        $storesForNew = [
            0 => 'new_product_discount_alomgyar',
            1 => 'new_product_discount_olcsokonyvek',
            2 => 'new_product_discount_nagyker',
        ];

        foreach ($stores as $store => $option) {
            $price_data['product_id'] = $this->updateBook->id;
            $price_data['store'] = $store;
            ProductPrice::firstOrCreate($price_data);
            $prices = [
                'price_list_original' => $this->listPrice,
                'price_sale_original' => round($this->listPrice * (1 - (option(($isNewDiscount ? $storesForNew[$store] : $option)) / 100))),
                'price_list' => $this->listPrice,
                'price_sale' => round($this->listPrice * (1 - (option(($isNewDiscount ? $storesForNew[$store] : $option)) / 100))),
                'discount_percent' => option(($isNewDiscount ? $storesForNew[$store] : $option)),
                'price_cart' => 0,
            ];
            $this->updateBook->price($store)->update($prices);
        }
    }
}
