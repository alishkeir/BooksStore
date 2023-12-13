<?php

namespace Database\Factories;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Customer;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use App\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = Country::all()->random();

        return [
            'customer_id' => Customer::all()->random()->id,
            'order_number' => 'ALOM222'.rand(30303, 90909),
            'payment_token' => 'as8d76as8d76a8s7',
            'status' => rand(0, 6),
            'shipping_fee' => $country->id == 1 ? ShippingMethod::all()->random()->fee_0 : $country->fee,
            'total_amount' => rand(3000, 10000),
            'total_quantity' => rand(1, 3),
            'has_ebook' => rand(0, 1),
            'store' => rand(0, 2),
            'country_id' => 0,
            'payment_method_id' => PaymentMethod::all()->random()->id,
            'shipping_method_id' => ShippingMethod::all()->random()->id,
            'invoice_url' => '/invoices/'.Str::random(6).'.pdf',
        ];
    }
}
