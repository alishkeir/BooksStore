<?php

namespace Database\Seeders;

use Alomgyar\Coupons\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
            'prefix' => 'TYPE1',
            'code' => 'ZHTYOO',
            'discount' => '50',
            'is_percent' => 1,
            'free_count' => '100',
            'used_count' => '0',
            'is_customer' => 0,
            //'customer_id' => null,
            //'customer_only_once' => 0,
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'status' => 1,
            'active_from' => '2021-03-22 00:00:00',
            'active_to' => '2021-09-23 23:00:00',
            'description' => 'Leírás',
        ]);
        Coupon::create([
            'prefix' => 'TYPE2',
            'code' => 'KJHZTR',
            'discount' => '400',
            'is_percent' => 0,
            'free_count' => '100',
            'used_count' => '0',
            'is_customer' => 0,
            //'customer_id' => null,
            //'customer_only_once' => 0,
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'status' => 1,
            'active_from' => '2021-03-22 00:00:00',
            'active_to' => '2021-09-23 23:00:00',
            'description' => 'Leírás',
        ]);
        Coupon::create([
            'prefix' => 'TYPE3',
            'code' => 'KJHZTR',
            'discount' => '400',
            'is_percent' => 0,
            'free_count' => '100',
            'used_count' => '0',
            'is_customer' => 1,
            'customer_id' => 1,
            'customer_only_once' => 0,
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'status' => 1,
            'active_from' => '2021-03-22 00:00:00',
            'active_to' => '2021-09-23 23:00:00',
            'description' => 'Leírás',
        ]);

//        Coupon::factory()->count(50)->create();
    }
}
