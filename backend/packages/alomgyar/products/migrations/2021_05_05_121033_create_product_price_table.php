<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->index();
            $table->integer('price_list')->nullable();
            $table->integer('price_sale')->nullable();
            $table->integer('price_cart')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->integer('price_list_original')->nullable();
            $table->integer('price_sale_original')->nullable();
            $table->tinyInteger('store')->nullable()->comment('0 - álomgyár, 1 - olcsókönyvek, 2 - nagyker');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price');
    }
}
