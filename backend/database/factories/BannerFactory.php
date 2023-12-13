<?php

namespace Database\Factories;

use Alomgyar\Banners\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    /** @var string */
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'main_banner' => 'banner/'.$this->faker->image('storage/app/public/banner', 1024, 500, null, false),
            'main_banner_title' => $this->faker->sentence(2),
            'main_banner_url' => 'www.skvad.com',
            'main_hero_banner' => 'banner/'.$this->faker->image('storage/app/public/banner', 500, 500, null, false),
            'main_hero_banner_title' => $this->faker->sentence(2),
            'main_hero_banner_url' => 'www.skvad.com',
            'shop_id' => 0,
            'status' => true,
        ];
    }
}
