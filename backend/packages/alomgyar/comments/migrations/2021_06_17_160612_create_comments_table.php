<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained('product')->onUpdate('cascade');
            $table->foreignId('post_id')->nullable()->constrained()->onUpdate('cascade');
            $table->string('entity_type')->comment('0-product, 1-post')->default(0);
            $table->unsignedTinyInteger('store')->comment('0 - álomgyár, 1 - olcsókönyvek, 2 - nagyker');
            $table->longText('comment')->nullable();
            $table->longText('original_comment')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0-banned, 1-new, 2-approved');
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
        Schema::dropIfExists('comments');
    }
}
