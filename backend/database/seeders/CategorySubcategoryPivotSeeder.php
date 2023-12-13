<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySubcategoryPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 50) as $index) {
            DB::table('category_subcategory')->insert([
                'category_id' => rand(1, 13),
                'subcategory_id' => rand(1, 390),
            ]);
        }
        foreach (range(1, 390) as $index) {
            DB::table('category_subcategory')->insert([
                'category_id' => rand(1, 13),
                'subcategory_id' => $index,
            ]);
        }
    }
}
