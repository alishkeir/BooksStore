<?php

namespace Database\Seeders;

use Alomgyar\Carts\Cart;
use Alomgyar\Customers\Customer;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Cart::factory()->count(10)->create();
        foreach (Customer::all() as $customer) {
            Cart::create([
                'customer_id' => $customer->id,
                'guest_token' => null,
                'order_id' => null,
                'total_quantity' => rand(1, 10),
                'total_amount' => rand(1000, 10000),
                'total_amount_full_price' => rand(1000, 10000),
                'store' => 0,
            ]);
        }

        foreach (range(1, 3) as $guest) {
            Cart::create([
                'customer_id' => null,
                'guest_token' => Cart::generateGuestToken(),
                'order_id' => null,
                'total_quantity' => rand(1, 10),
                'total_amount' => rand(1000, 10000),
                'total_amount_full_price' => rand(1000, 10000),
                'store' => 0,
            ]);
        }
    }
}
