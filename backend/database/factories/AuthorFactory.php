<?php

namespace Database\Factories;

use Alomgyar\Authors\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->unique()->slug,
            'description' => '<p>'.$this->faker->realText(rand(50, 300)).'</p>',
            'cover' => $this->faker->imageUrl(800, 600),
            'meta_title' => $this->faker->name,
            'meta_description' => $this->faker->sentence(rand(1, 5)),
            'status' => rand(0, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
