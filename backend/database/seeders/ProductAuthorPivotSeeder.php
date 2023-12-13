<?php

namespace Database\Seeders;

use Alomgyar\Authors\Author;
use Alomgyar\Products\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAuthorPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsCount = Product::count();
        $authorsCount = Author::count();

        foreach (range(1, 1500) as $index) {
            try {
                DB::table('product_author')->insert([
                    'product_id' => rand(1, $productsCount),
                    'author_id' => rand(1, $authorsCount),
                    'primary' => rand(0, 1),
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
