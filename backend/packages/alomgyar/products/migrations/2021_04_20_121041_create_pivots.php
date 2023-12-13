<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_author', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('author_id')->index();
            $table->tinyInteger('primary')->nullable();

            $table->foreign('author_id')->references('id')->on('author');
            $table->foreign('product_id')->references('id')->on('product');
        });
        Schema::create('product_subcategory', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('subcategory_id')->index();

            $table->foreign('subcategory_id')->references('id')->on('subcategory');
            $table->foreign('product_id')->references('id')->on('product');
        });
        Schema::create('category_subcategory', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->index();
            $table->bigInteger('subcategory_id')->index();

            $table->foreign('subcategory_id')->references('id')->on('subcategory');
            $table->foreign('category_id')->references('id')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_subcategory');
        Schema::dropIfExists('product_subcategory');
        Schema::dropIfExists('product_author');
    }
}
