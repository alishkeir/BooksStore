<?php

namespace Database\Seeders;

use Alomgyar\Categories\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Category::factory()->count(10)->create();
        $categories = [
            ['title' => 'Család és szülők', 'slug' => 'csalad-es-szulok'],
            ['title' => 'Gasztronómia', 'slug' => 'gasztronomia'],
            ['title' => 'Ifjúsági', 'slug' => 'ifjusagi'],
            ['title' => 'Életmód', 'slug' => 'eletmod'],
            ['title' => 'Gyerek', 'slug' => 'gyerek'],
            ['title' => 'Kaland', 'slug' => 'kaland'],
            ['title' => 'Életrajz', 'slug' => 'eletrajz'],
            ['title' => 'Gyereknevelés', 'slug' => 'gyerekneveles'],
            ['title' => 'Képregény', 'slug' => 'kepregeny'],
            ['title' => 'Erotika', 'slug' => 'erotika'],
            ['title' => 'Hobbi', 'slug' => 'hobbi'],
            ['title' => 'Ezotéria', 'slug' => 'ezoteria'],
            ['title' => 'Humor', 'slug' => 'humor'],
        ];
        foreach ($categories as $index => $category) {
            DB::table('category')->insert([
                'title' => $category['title'],
                'slug' => $category['slug'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
