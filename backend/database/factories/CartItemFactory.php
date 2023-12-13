<?php

namespace Database\Factories;

use Alomgyar\Carts\Cart;
use Alomgyar\Carts\CartItem;
use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => Cart::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'is_cart_price' => rand(0, 1),
            'quantity' => rand(1, 10),
        ];
    }
}
