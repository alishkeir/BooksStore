<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('method_id')->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('fee_0')->default(0);
            $table->unsignedInteger('fee_1')->default(0);
            $table->unsignedInteger('fee_2')->default(0);
            $table->boolean('status_0')->default(1);
            $table->boolean('status_1')->default(1);
            $table->boolean('status_2')->default(1);
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
        Schema::dropIfExists('shipping_methods');
    }
}
