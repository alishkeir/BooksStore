<?php

namespace Database\Seeders;

use Alomgyar\Carousels\Carousel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::ensureDirectoryExists('storage/app/public/carousel');

        Carousel::factory()->count(5)->create([
            'shop_id' => 0,
        ]);
        Carousel::factory()->count(5)->create([
            'shop_id' => 1,
        ]);
        Carousel::factory()->count(5)->create([
            'shop_id' => 2,
        ]);
    }
}
