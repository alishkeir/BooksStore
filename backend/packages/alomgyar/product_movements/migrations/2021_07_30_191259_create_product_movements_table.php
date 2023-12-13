<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_movements', function (Blueprint $table) {
            $table->id();
            $table->string('causer_type');
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->foreignId('product_id')->constrained('product');
            $table->unsignedInteger('stock_in');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - új bevételezett, 2 - ebből fogy, 3 - elfogyott');
            $table->integer('stock_out')->default(0);
            $table->string('source_type')->comment('beszállító, raktár, bolt');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedInteger('purchase_price')->nullable();
            $table->string('destination_type')->comment('raktárak közötti mozgazás, webshop eladásból fakadó, bolti eladásból fakadó, beszerzés, leltár');
            $table->unsignedBigInteger('destination_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_movements');
    }
}
