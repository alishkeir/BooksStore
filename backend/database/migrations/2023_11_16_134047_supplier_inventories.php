<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SupplierInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_inventories', function (Blueprint $table) {
            $table->bigInteger("id")->unsigned()->autoIncrement();
            $table->bigInteger("supplier_id")->unsigned();
            $table->bigInteger("product_id")->unsigned();
            $table->integer("stock");

            $table->foreign('supplier_id','fr_supplier_inventories_supplier_id')->references('id')->on('suppliers');
            $table->foreign('product_id','fr_supplier_inventories_product_id')->references('id')->on('product');
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
