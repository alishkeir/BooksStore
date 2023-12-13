<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliateDataToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('affiliate_code')->nullable();
            $table->string('affiliate_commission_percentage')->nullable();
            $table->string('affiliate_track_period')->nullable(); // in days
            $table->dateTime('affiliate_track_period_expires_at')->nullable(); // timestamp string
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('affiliate_code');
            $table->dropColumn('affiliate_commission_percentage');
            $table->dropColumn('affiliate_track_period');
            $table->dropColumn('affiliate_track_period_expires_at');
        });
    }
}
