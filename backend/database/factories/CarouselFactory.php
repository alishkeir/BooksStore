<?php

namespace Database\Factories;

use Alomgyar\Carousels\Carousel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarouselFactory extends Factory
{
    protected $model = Carousel::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'image' => 'carousel/'.$this->faker->image('storage/app/public/carousel', 640, 480, null, false),
            'shop_id' => array_rand([0, 1, 2]),
            'url' => url('/'),
            'order' => rand(1, 20),
        ];
    }
}
