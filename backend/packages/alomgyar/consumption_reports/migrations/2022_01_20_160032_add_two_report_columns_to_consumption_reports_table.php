<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoReportColumnsToConsumptionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consumption_reports', function (Blueprint $table) {
            $table->dropColumn('link_to_author_reports');
            $table->json('link_to_author_report')->nullable();
            $table->json('link_to_copyright_report')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consumption_reports', function (Blueprint $table) {
            $table->json('link_to_author_reports')->nullable();
            $table->dropColumn('link_to_author_report');
            $table->dropColumn('link_to_copyright_report');
        });
    }
}
