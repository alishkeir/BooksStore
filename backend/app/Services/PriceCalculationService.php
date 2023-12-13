<?php

namespace App\Services;

use Alomgyar\Products\ProductPrice;

class PriceCalculationService
{
    public function savePricesForProduct($bookId, $listPrice, $isNew = false)
    {
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
            $price_data['product_id'] = $bookId;
            $price_data['store'] = $store;

            $calculatedSalePrice = round($listPrice * (1 - (option(($isNew ? $storesForNew[$store] : $option)) / 100)));

            $productPrice = ProductPrice::create([
                'store' => $store,
                'product_id' => $bookId,
                'price_list_original' => $listPrice,
                'price_sale_original' => $calculatedSalePrice,
                'price_list' => $listPrice,
                'price_sale' => $calculatedSalePrice,
                'discount_percent' => option(($isNew ? $storesForNew[$store] : $option)),
                'price_cart' => 0,
            ]);
        }
    }
}
