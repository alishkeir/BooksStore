<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveOrdersForRecommenders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_orders_for_recommenders', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('customer_id');
            $table->integer('old_user_id');
            $table->string('order_code');

            $table->string('order_create_date')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /*
    SELECT product_to_cart_cart_id, product_to_cart_product_id, cart_user_id, customers.id, old_order.order_code
    FROM old_product_to_cart
    LEFT JOIN old_cart ON product_to_cart_cart_id = old_cart.cart_id
    LEFT JOIN customers ON customers.old_id = cart_user_id
    LEFT JOIN old_order ON old_order.order_cart_id = old_cart.cart_id
    WHERE product_to_cart_createdate > 2020-10-01
    AND old_cart.cart_user_id IS NOT NULL
    AND old_order.order_code IS NOT NULL
    LIMIT 50 */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_orders_for_recommenders');
    }
}
