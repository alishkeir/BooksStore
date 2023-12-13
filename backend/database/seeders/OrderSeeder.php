<?php

namespace Database\Seeders;

use Alomgyar\Countries\Country;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use App\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()->count(10)->create();
        $country = Country::all()->random();
        foreach (range(1, 41) as $index) {
            Order::create([
                'customer_id' => 2,
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
            ]);
        }
    }
}
