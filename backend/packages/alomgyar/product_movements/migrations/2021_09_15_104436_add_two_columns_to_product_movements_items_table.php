<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsToProductMovementsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements_items', function (Blueprint $table) {
            $table->unsignedInteger('purchase_price')->nullable()->after('stock_out');
            $table->unsignedInteger('remaining_quantity_from_report')->nullable()->after('purchase_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_movements_items', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
            $table->dropColumn('remaining_quantity_from_report');
        });
    }
}
