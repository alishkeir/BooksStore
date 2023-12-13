<?php

namespace Database\Seeders;

use Alomgyar\Methods\PaymentMethod;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_methods = [
            [
                'name' => 'Utánvét',
                'method_id' => 'cash_on_delivery',
                'description' => '-',
                'fee_0' => 0,
                'fee_1' => 0,
                'fee_2' => 0,
            ],
            [
                'name' => 'Előreutalás',
                'method_id' => 'transfer',
                'description' => '-',
                'fee_0' => 250,
                'fee_1' => 250,
                'fee_2' => 250,
            ],
            [
                'name' => 'Bankkártyával',
                'method_id' => 'card',
                'description' => '-',
                'fee_0' => 0,
                'fee_1' => 0,
                'fee_2' => 0,
            ],
            [
                'name' => 'Készpénz',
                'method_id' => 'cash',
                'description' => '-',
                'fee_0' => 0,
                'fee_1' => 0,
                'fee_2' => 0,
            ],
        ];

        foreach ($payment_methods as $method) {
            //TODO make method_id unique
            try {
                PaymentMethod::create($method);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
