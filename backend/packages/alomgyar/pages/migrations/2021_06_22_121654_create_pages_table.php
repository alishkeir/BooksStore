<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->string('cover')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar')->nullable();
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek')->nullable();
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker')->nullable();

            $table->unsignedTinyInteger('status')->default(0)->comment('0-inactive, 1-active');
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
        Schema::dropIfExists('pages');
    }
}
