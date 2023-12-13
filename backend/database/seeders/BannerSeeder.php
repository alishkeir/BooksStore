<?php

namespace Database\Seeders;

use Alomgyar\Banners\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::ensureDirectoryExists('storage/app/public/banner');

        Banner::truncate();

        Banner::factory()->create([
            'shop_id' => 0,
        ]);

        Banner::factory()->create([
            'shop_id' => 1,
        ]);

        Banner::factory()->create([
            'shop_id' => 2,
        ]);
    }
}
