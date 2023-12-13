<?php

namespace Database\Seeders;

use Alomgyar\Countries\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create(
            [
                'name' => 'Magyarország',
                'code' => 'HU',
                'fee' => 0,
                'status' => 1,
            ]
        );

        Country::create(
            [
                'name' => 'Szlovákia',
                'code' => 'SK',
                'fee' => 4000,
                'status' => 1,
            ]);

        Country::create(
            [
                'name' => 'Horvátország',
                'code' => 'HR',
                'fee' => 4000,
                'status' => 1,
            ]);

        Country::create(
            [
                'name' => 'Csehország',
                'code' => 'CZ',
                'fee' => 5000,
                'status' => 1,
            ]);

        Country::create(
            [
                'name' => 'Lengyelország',
                'code' => 'PL',
                'fee' => 5000,
                'status' => 1,
            ]);

        Country::create(
            [
                'name' => 'Románia',
                'code' => 'RO',
                'fee' => 5000,
                'status' => 1,
            ]
        );

        Country::create([
            'name' => 'Szlovákia',
            'code' => 'SK',
            'fee' => 4000,
            'status' => 1,
        ]);

        Country::create(
            [
                'name' => 'Ausztria',
                'code' => 'AT',
                'fee' => 4000,
                'status' => 1,
            ]
        );

        Country::create(
            [
                'name' => 'Szlovákia',
                'code' => 'SK',
                'fee' => 4000,
                'status' => 1,
            ]
        );

        Country::create(
            [
                'name' => 'Írország',
                'code' => 'IE',
                'fee' => 6000,
                'status' => 1,
            ]
        );

        Country::create(
            [
                'name' => 'Szlovénia',
                'code' => 'SI',
                'fee' => 4000,
                'status' => 1,
            ]
        );

        Country::create([
            'name' => 'Németország',
            'code' => 'DE',
            'fee' => 5000,
            'status' => 1,
        ]);
    }
}
