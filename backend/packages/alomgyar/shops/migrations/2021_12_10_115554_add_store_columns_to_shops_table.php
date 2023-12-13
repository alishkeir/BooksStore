<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreColumnsToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar')->nullable();
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek')->nullable();
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('store_0');
            $table->dropColumn('store_1');
            $table->dropColumn('store_2');
        });
    }
}
