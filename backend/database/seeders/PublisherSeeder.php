<?php

namespace Database\Seeders;

use Alomgyar\Publishers\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Publisher::factory()->count(10)->create();
    }
}
