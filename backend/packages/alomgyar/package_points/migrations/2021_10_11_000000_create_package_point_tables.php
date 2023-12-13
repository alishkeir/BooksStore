<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePointTables extends Migration
{
    public function up()
    {
        Schema::create('package_points_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->default(null);
            $table->string('partner_id')->nullable()->default(null);
            $table->string('shop_id')->nullable()->default(null);
            $table->string('customer_id')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->timestamp('mail_sent_at')->nullable()->default(null);
            $table->timestamp('collected')->nullable()->default(null);
            $table->enum('status', ['waiting', 'shipping', 'arrived', 'completed', 'canceled'])->default('waiting');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('package_points_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default(null);
            $table->string('link')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('package_points_shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->text('open')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_points_shops');
        Schema::dropIfExists('package_points_partners');
        Schema::dropIfExists('package_points_packages');
    }
}
