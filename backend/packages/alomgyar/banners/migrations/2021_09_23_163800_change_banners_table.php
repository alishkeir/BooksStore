<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBannersTable extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('main_banner_title')->nullable()->default(null);
            $table->string('main_banner_url')->nullable()->default(null);
            $table->string('main_hero_banner_title')->nullable()->default(null);
            $table->string('main_hero_banner_url')->nullable()->default(null);

            $table->dropColumn('title');
            $table->dropColumn('url');
        });
    }

    public function down()
    {
    }
}
