<?php

namespace Database\Seeders;

use Alomgyar\Shops\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::factory()->count(10)->create();
    }
}
