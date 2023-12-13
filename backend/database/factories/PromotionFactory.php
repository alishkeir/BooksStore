<?php

namespace Database\Factories;

use Alomgyar\Promotions\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promotion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $domain = env('BACKEND_URL');

        return [
            'title' => $this->faker->sentence(rand(1, 3)).' akció',
            'slug' => $this->faker->slug().'-akcio',
            'cover' => $domain.'/demo/prom-1000x200.png',
            'list_image_xl' => $domain.'/demo/prom-640x360.png',
            'list_image_sm' => $domain.'/demo/prom-400x320.png',
            'meta_title' => $this->faker->sentence(rand(1, 5)),
            'meta_description' => $this->faker->sentence(rand(1, 5)),
            'status' => 1,
            'active_from' => '2021-03-'.rand(10, 27),
            'active_to' => '2021-08-'.rand(10, 27),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
