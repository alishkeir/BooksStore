<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerPreordersTable extends Migration
{
    public function up(): void
    {
        Schema::table('customer_preorders', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('customer_preorders', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
    }
}
