<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Consumptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumptions', function (Blueprint $table) {
            $table->bigInteger("id")->unsigned()->autoIncrement();
            $table->bigInteger("supplier_id")->unsigned();
            $table->bigInteger("product_id")->unsigned();
            $table->integer("quantity");
            $table->integer("price")->nullable();
            $table->integer("remaining_quantity")->nullable();
            $table->dateTime("created_at");
            $table->dateTime("updated_at");

            $table->foreign('supplier_id','fr_consumptions_supplier_id')->references('id')->on('suppliers');
            $table->foreign('product_id','fr_consumptions_product_id')->references('id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
