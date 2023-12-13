<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicPreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_preorders', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->foreignId('product_id')->constrained('product');
            $table->timestamps();

            $table->unique(['email', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_preorders');
    }
}
