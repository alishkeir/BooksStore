<?php

namespace Database\Seeders;

use Alomgyar\Carts\CartItem;
use Illuminate\Database\Seeder;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            CartItem::factory()->count(30)->create();
        } catch (\Exception $e) {
        }
    }
}
