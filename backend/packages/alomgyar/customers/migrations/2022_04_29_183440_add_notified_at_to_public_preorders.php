<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifiedAtToPublicPreorders extends Migration
{
    public function up(): void
    {
        Schema::table('public_preorders', function (Blueprint $table) {
            $table->timestamp('notified_at')->after('product_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('public_preorders', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
    }
}
