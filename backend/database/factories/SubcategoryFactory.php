<?php

namespace Database\Factories;

use Alomgyar\Subcategories\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subcategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(rand(1, 3)),
            'slug' => $this->faker->unique()->slug(rand(1, 3)),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
