<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRecommendersTableStore extends Migration
{
    public function up()
    {
        Schema::table('recommenders', function (Blueprint $table) {
            $table->tinyInteger('store')->default(0);
        });
    }

    public function down()
    {
        Schema::table('recommenders', function (Blueprint $table) {
            $table->dropColumn('store');
        });
    }
}
