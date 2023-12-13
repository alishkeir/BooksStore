<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RankedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('ranked_products', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id');
                $table->integer('rank')->default(0);
                $table->enum('type', ['pre', 'sold', 'e_sold', 'discount_sold']);
                $table->integer('store_id');

                $table->timestamps();
            });
        } catch (Exception $e) {
        }
    }

    public function down()
    {
        Schema::dropIfExists('ranked_products');
    }
}
