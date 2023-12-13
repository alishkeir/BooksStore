<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_extra', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('mobi_url')->nullable();
            $table->string('mobi_size')->nullable();
            $table->string('epub_url')->nullable();
            $table->string('epub_size')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //$table->integer('number')->nullable();
            //$table->unsignedTinyInteger('available')->nullable();
            //$table->tinyInteger('storageCondition')->nullable();
            //$table->string('unitType')->nullable();
            //$table->string('unitBase')->nullable();
            //$table->smallInteger('unitCount')->nullable();
            //$table->unsignedTinyInteger('supplyType')->nullable();
            //$table->string('manufacturerName')->nullable();
            //$table->string('product_url')->nullable();
            //$table->unsignedTinyInteger('warrantyType')->nullable();
            //$table->smallInteger('warrantyPeriod')->nullable();
            //$table->string('productType')->nullable();
            //$table->string('manufacturerNumber')->nullable();
            //$table->unsignedTinyInteger('serialNumberUsage')->nullable();
            //$table->string('binding')->nullable();
            //$table->integer('orderUnit')->nullable();
            //$table->integer('stockFree')->nullable();
            //$table->string('currency')->nullable();
            //$table->smallInteger('stockExpectedQuantity')->nullable();
            //$table->smallInteger('stockWaitingReceipts')->nullable();
            //$table->string('stockAvailableDate')->nullable();
            //$table->smallInteger('stockOuterType')->nullable();
            //$table->smallInteger('stockOuterQuantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_extra');
    }
}
