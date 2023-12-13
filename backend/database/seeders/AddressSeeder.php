<?php

namespace Database\Seeders;

use Alomgyar\Customers\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::factory()->count(20)->create();
    }
}
