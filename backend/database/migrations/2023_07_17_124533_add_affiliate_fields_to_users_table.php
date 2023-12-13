<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliateFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_affiliate')->nullable();
            $table->string('affiliate_name')->nullable();
            $table->string('affiliate_country')->nullable();
            $table->string('affiliate_zip')->nullable();
            $table->string('affiliate_city')->nullable();
            $table->string('affiliate_address')->nullable();
            $table->string('affiliate_vat')->nullable();
            $table->string('affiliate_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_affiliate');
            $table->dropColumn('affiliate_name');
            $table->dropColumn('affiliate_country');
            $table->dropColumn('affiliate_zip');
            $table->dropColumn('affiliate_city');
            $table->dropColumn('affiliate_address');
            $table->dropColumn('affiliate_vat');
            $table->dropColumn('affiliate_code');
        });
    }
}
