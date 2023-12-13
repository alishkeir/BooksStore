<?php

namespace Database\Seeders;

use Alomgyar\Warehouses\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Warehouse::create([
                'id' => 1,
                'title' => 'GPS',
                'type' => 1,
                'city' => 'Budapest',
            ]);
        } catch (QueryException $queryException) {
        }
    }
}
