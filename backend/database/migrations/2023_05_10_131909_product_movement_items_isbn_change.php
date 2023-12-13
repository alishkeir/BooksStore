<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductMovementItemsIsbnChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_movements_items_isbn_change', function (Blueprint $table) {
            $table->unsignedBigInteger('product_movement_items_id')->index('product_movement_items_id_index');
            $table->unsignedBigInteger('old_product_id')->index('old_product_id_index');
            $table->unsignedBigInteger('new_product_id')->index('new_product_id_index');
            $table->timestamp('created_at')->useCurrent();
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
