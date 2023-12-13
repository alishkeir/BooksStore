<?php

namespace Database\Seeders;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerPreorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Customer::all() as $customer) {
            DB::table('customer_preorders')->insert([
                'customer_id' => $customer->id,
                'product_id' => Product::whereState(0)->get()->random()->id,
            ]);
            DB::table('customer_preorders')->insert([
                'customer_id' => $customer->id,
                'product_id' => Product::whereState(1)->get()->random()->id,
            ]);
        }

        foreach (range(1, 41) as $index) {
            try {
                DB::table('customer_preorders')->insert([
                    'customer_id' => 2,
                    'product_id' => Product::find(rand(1, 1000))->id,
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
