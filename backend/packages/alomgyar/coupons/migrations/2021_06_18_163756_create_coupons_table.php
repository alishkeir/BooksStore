<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->default('KUPON');
            $table->string('code')->nullable();
            $table->integer('discount')->nullable();
            $table->unsignedTinyInteger('is_percent')->default(1)->comment('0-amount, 1-percent');
            $table->bigInteger('free_count')->default(1)->nullable();
            $table->bigInteger('used_count')->default(0)->nullable();
            $table->unsignedTinyInteger('is_customer')->default(1)->comment('0-everyone, 1-customer');
            $table->bigInteger('customer_id')->nullable();
            $table->unsignedTinyInteger('customer_only_once')->default(1)->comment('0-nolimit, 1-onlyonce');

            $table->unsignedTinyInteger('store_0')->default(1)->comment('alomgyar');
            $table->unsignedTinyInteger('store_1')->default(1)->comment('olcsokonyvek');
            $table->unsignedTinyInteger('store_2')->default(1)->comment('nagyker');

            $table->timestamp('active_from')->nullable();
            $table->timestamp('active_to')->nullable();

            $table->longText('description')->nullable();

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
        Schema::dropIfExists('coupons');
    }
}
