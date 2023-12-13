<?php

namespace Database\Factories;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $domain = env('BACKEND_URL');
        $covers = [
            'dummy_1_400-604.jpg',
            'dummy_1_400-592.jpg',
            'dummy_1_400-591.jpg',
            'dummy_1_400-613.jpg',
            'dummy_1_400-603.jpg',
            'dummy_1_400-474.jpg',
            'dummy_1_400-640.jpg',
            'dummy_1_243-373.jpg',
            'dummy_1_400-335.jpg',
            'dummy_1_400-601.jpg',
            'dummy_2_400-640.jpg',
            'dummy_2_400-606.jpg',

        ];

        return [
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(rand(1, 5)),
            'status' => 1,
            //'cover' => '/boritok/' . $this->faker->slug() . '.jpg',
            'cover' => $domain.'/demo/'.$covers[rand(0, 11)],
            'orders_count_0' => rand(0, 500),
            'orders_count_1' => rand(0, 500),
            'orders_count_2' => rand(0, 500),
            'preorders_count_0' => rand(0, 500),
            'preorders_count_1' => rand(0, 500),
            'preorders_count_2' => rand(0, 500),
            'publisher_id' => rand(1, 10),
            'isbn' => $this->faker->isbn13,
            'release_year' => $this->faker->date('Y'),
            'number_of_pages' => rand(300, 1000),
            'tax_rate' => 5,
            'state' => rand(0, 2),
            'type' => rand(0, 1),
            'published_at' => $this->faker->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
