<?php

use Database\Seeders\DpdAndSamedayShippingMethodSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AddDiscountedFeeToShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->unsignedSmallInteger('discounted_fee_0')->default(0);
            $table->unsignedSmallInteger('discounted_fee_1')->default(0);
            $table->unsignedSmallInteger('discounted_fee_2')->default(0);
        });

        Artisan::call('db:seed', [
            '--class' => DpdAndSamedayShippingMethodSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('discounted_fee_0');
            $table->dropColumn('discounted_fee_1');
            $table->dropColumn('discounted_fee_2');
        });
    }
}
