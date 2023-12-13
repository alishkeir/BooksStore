<?php

namespace Database\Seeders;

use Alomgyar\Customers\Customer;
use Alomgyar\Posts\Post;
use Alomgyar\Products\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 100) as $index) {
            try {
                DB::table('comments')->insert([
                    'customer_id' => Customer::all()->random()->id,
                    'product_id' => Product::find(rand(1, 20))->id,
                    'post_id' => Post::all()->random()->id,
                    'entity_type' => 0,
                    'comment' => 'A jövőheti lottószámok: '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' ',
                    'original_comment' => 'A jövőheti lottószámok: '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' '.rand(1, 50).' ',
                    'store' => 0,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }
}
