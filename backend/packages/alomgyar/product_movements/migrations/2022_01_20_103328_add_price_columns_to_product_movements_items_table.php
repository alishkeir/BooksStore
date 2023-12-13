<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnsToProductMovementsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements_items', function (Blueprint $table) {
            $table->unsignedInteger('sale_price')->nullable()->after('purchase_price')->comment('Eladáskori ár');
            $table->unsignedInteger('discount')->nullable()->after('sale_price')->comment('Kedvezmény mértéke');
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
            $table->dropColumn('sale_price');
            $table->dropColumn('discount');
        });
    }
}
