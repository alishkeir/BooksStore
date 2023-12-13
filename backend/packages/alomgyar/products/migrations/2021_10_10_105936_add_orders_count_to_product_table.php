<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdersCountToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->renameColumn('orders_count', 'orders_count_0');
            $table->unsignedInteger('orders_count_1')->nullable()->after('orders_count');
            $table->unsignedInteger('orders_count_2')->nullable()->after('orders_count_1');
            $table->unsignedInteger('preorders_count_0')->nullable()->after('orders_count_2');
            $table->unsignedInteger('preorders_count_1')->nullable()->after('preorders_count_0');
            $table->unsignedInteger('preorders_count_2')->nullable()->after('preorders_count_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->renameColumn('orders_count_0', 'orders_count');
            $table->dropColumn('orders_count_1');
            $table->dropColumn('orders_count_2');
            $table->dropColumn('preorders_count_0');
            $table->dropColumn('preorders_count_1');
            $table->dropColumn('preorders_count_2');
        });
    }
}
