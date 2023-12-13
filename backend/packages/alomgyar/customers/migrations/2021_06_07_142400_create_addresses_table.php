<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('city');
            $table->string('zip_code');
            $table->string('address');
            $table->string('address_phone')->nullable();
            $table->string('address_email')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->enum('type', ['billing', 'shipping']);
            $table->enum('role', ['customer', 'order']);
            $table->unsignedTinyInteger('entity_type')->nullable()->comment('1-privát, 2-cég');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
