<?php

namespace Database\Factories;

use Alomgyar\Products\Product;
use App\Order;
use App\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::with('prices')->find(rand(1, 1000));
        $quantity = rand(1, 3);

        return [
            'order_id' => Order::find(rand(1, 10))->id,
            'product_id' => $product->id,
            'price' => $product->prices->price_sale,
            'original_price' => $product->prices->price_list,
            'cart_price' => rand(0, 1),
            'quantity' => $quantity,
            'total' => $product->prices->price_sale * $quantity,
        ];
    }
}
