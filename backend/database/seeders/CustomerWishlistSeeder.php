<?php

namespace Database\Seeders;

use Alomgyar\Products\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerWishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 41) as $index) {
            try {
                DB::table('customer_wishlist')->insert([
                    'customer_id' => 2,
                    'product_id' => Product::find(rand(1, 1000))->id,
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
