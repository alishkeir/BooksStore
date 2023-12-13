<?php

namespace Database\Seeders;

use Alomgyar\Customers\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'firstname' => 'Béla',
            'lastname' => 'Negyedik',
            'email' => 'bela@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'marketing_accepted' => 1,
            'author_follow_up' => 0,
            'comment_follow_up' => 0,
            'status' => Customer::STATUS_ACTIVE,
            'store' => 0,
        ]);

        Customer::create([
            'firstname' => 'Juliska',
            'lastname' => 'Hopp',
            'email' => 'juliska@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'marketing_accepted' => 1,
            'author_follow_up' => 1,
            'comment_follow_up' => 0,
            'status' => Customer::STATUS_ACTIVE,
            'store' => 0,
        ]);

        Customer::create([
            'firstname' => 'Anna',
            'lastname' => 'Major',
            'email' => 'anna@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'marketing_accepted' => 1,
            'author_follow_up' => 1,
            'comment_follow_up' => 1,
            'status' => Customer::STATUS_ACTIVE,
            'store' => 0,
        ]);

//        Customer::factory()->count(50)->create();
    }
}
