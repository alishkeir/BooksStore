<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        foreach (range(1, 1000) as $index) {
            $store = 0;
            while ($store <= 2) {
                $list = rand(4000, 5000);
                $sale = rand(3000, 4000);
                DB::table('product_price')->insert([
                    'product_id' => $index,
                    'price_list' => rand(4000, 5000),
                    'price_sale' => rand(3000, 4000),
                    'price_cart' => rand(0, 1) ? rand(1500, 2000) : 0,
                    'price_list_original' => rand(4000, 5000),
                    'price_sale_original' => rand(3000, 4000),
                    'discount_percent' => 100 - (($sale / $list) * 100),
                    'store' => $store,
                ]);
                $store++;
            }
        }
    }
}
