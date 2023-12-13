<?php

namespace Database\Factories;

use Alomgyar\Shops\Shop;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $opening_hours = [
            0 => [
                [
                    'days' => 'hétfő-kedd',
                    'hours' => '09-18',
                ],
                [
                    'days' => 'szombat-vasárnap',
                    'hours' => 'ZÁRVA',
                ],
            ],
            1 => [
                [
                    'days' => 'hétfő',
                    'hours' => '09-18',
                ],
                [
                    'days' => 'kedd',
                    'hours' => '09-18',
                ], [
                    'days' => 'szerda',
                    'hours' => '10-18',
                ], [
                    'days' => 'csütörtök',
                    'hours' => '12-18',
                ], [
                    'days' => 'péntek',
                    'hours' => '06-22',
                ], [
                    'days' => 'szombat',
                    'hours' => '09-14',
                ],
                [
                    'days' => 'vasárnap',
                    'hours' => 'ZÁRVA',
                ],
            ],
        ];

        $city = $this->faker->city;
        $address = $this->faker->streetAddress;

        return [
            'title' => $city.', '.$address,
            'description' => $this->faker->paragraph(rand(1, 2)),
            'zip_code' => $this->faker->postcode,
            'city' => $city,
            'address' => $address,
            'status' => Shop::STATUS_ACTIVE,
            'phone' => $this->faker->phoneNumber,
            'email' => Str::lower($city).'@alomgyar.hu',
            'facebook' => 'https://facebook.com/'.$this->faker->slug,
            'cover' => env('BACKEND_URL').'/demo/prom-400x320.png',
            'latitude' => rand(46, 47).'.'.rand(479688, 611790),
            'longitude' => rand(17, 20).'.'.rand(503220, 965201),
            'opening_hours' => $opening_hours[rand(0, 1)],
            'created_at' => $this->faker->dateTime('now', 'Europe/Budapest'),
            'updated_at' => $this->faker->dateTime('now', 'Europe/Budapest'),
        ];
    }
}
