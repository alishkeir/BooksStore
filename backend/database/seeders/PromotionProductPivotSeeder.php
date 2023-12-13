<?php

namespace Database\Seeders;

use Alomgyar\Promotions\Promotion;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionProductPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $promotionsCount = Promotion::count();

        foreach (range(1, 500) as $index) {
            try {
                DB::table('promotion_product')->insert([
                    'product_id' => $index,
                    'promotion_id' => rand(1, $promotionsCount),
                    'price_sale_0' => rand(1000, 2000),
                    'price_sale_1' => rand(1500, 2500),
                    'price_sale_2' => rand(500, 1000),
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
