<?php

namespace Database\Seeders;

use Alomgyar\Authors\Author;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 42) as $index) {
            try {
                DB::table('customer_authors')->insert([
                    'customer_id' => 2,
                    'author_id' => Author::find(rand(1, 20))->id,
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
