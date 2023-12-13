<?php

namespace Database\Factories;

use Alomgyar\Categories\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word(rand(2, 3)),
            'slug' => $this->faker->unique()->slug(rand(1, 2)),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
