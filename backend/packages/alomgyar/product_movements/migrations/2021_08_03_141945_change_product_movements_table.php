<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->unsignedInteger('stock_in')->nullable()->change();
            $table->unsignedSmallInteger('destination_type')->comment('0 - raktárak közötti mozgatás, 1 - webshop eladásból fakadó, 2 - bolti eladásból fakadó, 3 - beszerzés, 4 - leltár')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->unsignedInteger('stock_in')->change();
            $table->string('destination_type')->comment('raktárak közötti mozgazás, webshop eladásból fakadó, bolti eladásból fakadó, beszerzés, leltár')->change();
        });
    }
}
