<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateConsumptionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('consumption_reports');
        Schema::create('consumption_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->unsignedInteger('number_of_books');
            $table->unsignedInteger('number_of_suppliers');
            $table->string('link_to_report');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumption_reports');
    }
}
