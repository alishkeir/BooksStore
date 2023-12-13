<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSubcategoryPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //all products will have at least one subcategory
        foreach (range(1, 1000) as $index) {
            DB::table('product_subcategory')->insert([
                'product_id' => $index,
                'subcategory_id' => rand(1, 390),
            ]);
        }
        //all subcategory will have more than one products
        foreach (range(1, 390) as $index) {
            foreach (range(1, rand(2, 6)) as $ii) {
                DB::table('product_subcategory')->insert([
                    'product_id' => rand(1, 1000),
                    'subcategory_id' => $index,
                ]);
            }
        }
    }
}
