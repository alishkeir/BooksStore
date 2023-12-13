<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade');
            $table->string('order_number')->nullable();
            $table->string('payment_token')->nullable();
            $table->string('guest_token')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0-piszkozat, 1-fizetésre vár, 2-folyamatban, 3-osszekeszitve, 4-kiszállítás alatt, 5-teljesítve, 6-visszaküldve');
            $table->unsignedTinyInteger('payment_status')->default(0)->comment('0-draft, 1-error, 2-canceled, 3-paid');
            $table->unsignedInteger('shipping_fee')->nullable();
            $table->decimal('total_amount');
            $table->decimal('total_quantity');
            $table->unsignedInteger('has_ebook')->nullable()->default(0);
            $table->unsignedTinyInteger('store')->comment('0 - álomgyár, 1 - olcsókönyvek, 2 - nagyker, 3 - boltokban');
            $table->integer('country_id');
            $table->foreignId('payment_method_id')->constrained()->onUpdate('cascade');
            $table->foreignId('shipping_method_id')->constrained()->onUpdate('cascade');
            $table->string('invoice_url')->nullable();
            $table->json('shipping_data')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
