<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreToPublicPreorders extends Migration
{
    public function up(): void
    {
        Schema::table('public_preorders', function (Blueprint $table) {
            $table->tinyInteger('store')->nullable()->comment('0 - álomgyár, 1 - olcsókönyvek, 2 - nagyker');
        });
    }

    public function down(): void
    {
        Schema::table('public_preorders', function (Blueprint $table) {
            $table->dropColumn('store');
        });
    }
}
