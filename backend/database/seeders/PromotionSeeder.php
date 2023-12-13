<?php

namespace Database\Seeders;

use Alomgyar\Promotions\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Promotion::factory()->count(4)->create();
    }
}
