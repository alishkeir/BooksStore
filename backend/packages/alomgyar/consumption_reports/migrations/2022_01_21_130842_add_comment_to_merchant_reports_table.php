<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentToMerchantReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_reports', function (Blueprint $table) {
            $table->mediumText('comment')->nullable()->comment('Számla megjegyzés része')->after('invoice_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_reports', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
}
