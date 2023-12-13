<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerchantColumnsToWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            $table->string('billing_business_name')->nullable()->after('invoice_prefix');
            $table->string('billing_vat_number')->nullable()->after('billing_business_name');
            $table->string('billing_city')->nullable()->after('billing_vat_number');
            $table->string('billing_zip_code')->nullable()->after('billing_city');
            $table->string('billing_address')->nullable()->after('billing_zip_code');
            $table->boolean('is_merchant')->default(0)->after('billing_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            $table->dropColumn('billing_business_name');
            $table->dropColumn('billing_vat_number');
            $table->dropColumn('billing_city');
            $table->dropColumn('billing_zip_code');
            $table->dropColumn('billing_address');
            $table->dropColumn('is_merchant');
        });
    }
}
