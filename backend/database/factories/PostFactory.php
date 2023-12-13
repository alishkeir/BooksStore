<?php

namespace Database\Factories;

use Alomgyar\Posts\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = rand(1, 5);
        $i = 0;
        $description = '';
        while ($i < $paragraphs) {
            $description .= '<p>'.$this->faker->realText(rand(200, 1000)).'</p>';
            $i++;
        }

        return [
            'title' => $this->faker->sentence(rand(1, 3)),
            'lead' => $this->faker->sentence(rand(5, 10)),
            'slug' => $this->faker->slug(),
            'cover' => env('BACKEND_URL').'/demo/dummy_1_400-335.jpg',
            'meta_title' => $this->faker->sentence(rand(1, 5)),
            'meta_description' => $this->faker->sentence(rand(1, 5)),
            'body' => $description,
            'status' => 1,
            'store_0' => 1,
            'store_1' => 0,
            'store_2' => 0,
            'published_at' => rand(2019, 2021).'-'.rand(10, 12).'-'.rand(10, 30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
