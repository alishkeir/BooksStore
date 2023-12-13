<?php

namespace Database\Seeders;

use Alomgyar\Products\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(1000)->create();
        Product::find(1)->update([
            'orders_count_0' => 501,
            'orders_count_1' => 501,
            'orders_count_2' => 501,
            'state' => 0,
            'type' => 0,
        ]);
    }
}
