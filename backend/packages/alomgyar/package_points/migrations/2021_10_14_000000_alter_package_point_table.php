<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackagePointTable extends Migration
{
    public function up()
    {
        Schema::table('package_points_packages', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'customer');
        });
    }
}
