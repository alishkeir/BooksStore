<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product');
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('cover')->nullable();
            $table->unsignedInteger('orders_count')->nullable();
            $table->bigInteger('isbn')->nullable();
            $table->integer('release_year')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->smallInteger('tax_rate')->nullable();
            $table->unsignedTinyInteger('state')->default(0)->comment('0-normal, 1-pre, 2-manual');
            $table->unsignedTinyInteger('type')->nullable()->default(0)->comment('0-book, 1-ebook, 1-audio');
            $table->timestamp('published_at')->nullable();
            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar');
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek');
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker');
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
        Schema::dropIfExists('product');
    }
}
