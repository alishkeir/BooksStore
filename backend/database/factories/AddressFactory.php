<?php

namespace Database\Factories;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use App\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand = rand(0, 1);

        return [
            'last_name' => $rand === 0 ? $this->faker->lastName : '',
            'first_name' => $rand === 0 ? $this->faker->firstName : '',
            'business_name' => $rand === 1 ? $this->faker->company : '',
            'vat_number' => $rand === 1 ? $this->faker->regexify('[0-9]{7}-[1-2]{1}-[0-9]{2}') : '',
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'address' => $this->faker->streetAddress,
            //            'comment',
            'country_id' => Country::all()->random()->id,
            'type' => rand(0, 1) === 0 ? 'billing' : 'shipping',
            'role' => $rand === 0 ? 'customer' : 'order',
            'entity_type' => $rand === 0 ? 1 : 2,
            'role_id' => $rand === 0 ? Customer::all()->random()->id : Order::all()->random()->id,
        ];
    }
}
