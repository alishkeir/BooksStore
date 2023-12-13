<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('cover')->nullable();
            $table->string('list_image_xl')->nullable();
            $table->string('list_image_sm')->nullable();
            $table->timestamp('active_from')->nullable();
            $table->timestamp('active_to')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar')->nullable();
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek')->nullable();
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('promotion_product', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->bigInteger('promotion_id')->index();
            $table->bigInteger('product_id')->index();

            $table->integer('price_sale_0')->nullable();
            $table->integer('price_sale_1')->nullable();
            $table->integer('price_sale_2')->nullable();

            $table->foreign('promotion_id')->references('id')->on('promotion');
            $table->foreign('product_id')->references('id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion');
        Schema::dropIfExists('promotion_product');
    }
}
