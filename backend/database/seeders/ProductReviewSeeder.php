<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 200) as $index) {
            DB::table('product_review')->insert([
                'product_id' => $index,
                'customer_id' => rand(1, 2),
                'review' => rand(1, 5),
                'store' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach (range(1, 50) as $index) {
            DB::table('product_review')->insert([
                'product_id' => $index,
                'customer_id' => rand(1, 2),
                'review' => rand(1, 5),
                'store' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
