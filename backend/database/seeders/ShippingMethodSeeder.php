<?php

namespace Database\Seeders;

use Alomgyar\Methods\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingMethod::create([
            'name' => 'Házhozszállítás',
            'method_id' => 'home',
            'description' => '-',
            'fee_0' => 990,
            'fee_1' => 990,
            'fee_2' => 990,
        ]);
        ShippingMethod::create([
            'name' => 'Csomagponton',
            'method_id' => 'box',
            'description' => '-',
            'fee_0' => 0,
            'fee_1' => 0,
            'fee_2' => 0,
        ]);
        ShippingMethod::create([
            'name' => 'Álomgyár könyvesboltban',
            'method_id' => 'store',
            'description' => '-',
            'fee_0' => 0,
            'fee_1' => 0,
            'fee_2' => 0,
        ]);
        ShippingMethod::create([
            'name' => 'Nincs',
            'method_id' => 'none',
            'description' => '-',
            'fee_0' => 0,
            'fee_1' => 0,
            'fee_2' => 0,
        ]);
    }
}
