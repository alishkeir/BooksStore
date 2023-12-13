<?php

namespace Database\Factories;

use Alomgyar\Publishers\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublisherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Publisher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name.' Kft.',
            'description' => $this->faker->sentence(rand(1, 5)),
            'status' => rand(0, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
