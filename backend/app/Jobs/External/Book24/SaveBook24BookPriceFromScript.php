<?php

namespace App\Jobs\External\Book24;

use Alomgyar\Products\ProductPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveBook24BookPriceFromScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $book, public $newBook, public $isNew)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // CONVERT BACK TO XML
        $this->book = simplexml_load_string($this->book);

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
            $price_data['product_id'] = $this->newBook->id;
            $price_data['store'] = $store;
            ProductPrice::create($price_data);
            $calculatedSalePrice = round($this->book->ListPrice * (1 - (option(($this->isNew ? $storesForNew[$store] : $option)) / 100)));
            $prices = [
                'price_list_original' => $this->book->ListPrice,
                'price_sale_original' => $calculatedSalePrice,
                'price_list' => $this->book->ListPrice,
                'price_sale' => $calculatedSalePrice,
                'discount_percent' => option(($this->isNew ? $storesForNew[$store] : $option)),
                'price_cart' => 0,
            ];

            $this->newBook->price($store)->update($prices);
        }
    }
}
