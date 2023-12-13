<?php

namespace Database\Seeders;

use Alomgyar\Products\Product;
use App\Order;
use App\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderItem::factory()->count(20)->create();

        $products = Product::with('prices')->whereType(1)->limit(42)->get();

        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => Order::where('customer_id', 2)->get()->random()->id,
                'product_id' => $product->id ?? rand(1, 1000),
                'price' => $product->prices?->price_sale,
                'original_price' => $product->prices?->price_list,
                'cart_price' => rand(0, 1),
                'quantity' => 1,
                'total' => $product->prices?->price_sale,
            ]);
        }
    }
}
