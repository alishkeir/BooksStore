<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('posts');
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->longText('lead')->nullable();
            $table->longText('body')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('cover')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar');
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek');
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker');
            $table->date('published_at')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
