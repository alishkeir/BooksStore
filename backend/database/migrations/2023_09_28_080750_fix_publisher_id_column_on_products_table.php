<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixPublisherIdColumnOnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NEED THIS KEY CHECK = 0 BECAUSE CORRUPTED DB DATA
        // I THINK THERE ARE SOME REFERENCES TO PUBLISHERS WHO ARE ALREADY DELETED
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('product', function (Blueprint $table) {
            $table->foreign('publisher_id')->references('id')->on('publishers');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
