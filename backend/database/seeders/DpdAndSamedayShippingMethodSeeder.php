<?php

namespace Database\Seeders;

use Alomgyar\Methods\ShippingMethod;
use Illuminate\Database\Seeder;

class DpdAndSamedayShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingMethod::firstOrCreate([
            'name' => 'DPD',
            'method_id' => 'dpd',
        ], [
            'description' => '-',
            'fee_0' => 1300,
            'fee_1' => 1300,
            'fee_2' => 1300,
        ]);
        ShippingMethod::firstOrCreate([
            'name' => 'Sameday',
            'method_id' => 'sameday',
        ], [
            'description' => '-',
            'fee_0' => 1300,
            'fee_1' => 1300,
            'fee_2' => 1300,
        ]);
    }
}
