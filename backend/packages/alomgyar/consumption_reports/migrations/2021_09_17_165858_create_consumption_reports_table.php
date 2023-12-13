<?php

use Alomgyar\Products\Product;
use Alomgyar\Suppliers\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->foreignIdFor(Supplier::class);
            $table->foreignIdFor(Product::class);
            $table->unsignedInteger('total_sales');
            $table->unsignedInteger('purchase_price');
            $table->unsignedInteger('total_amount');
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
        Schema::dropIfExists('consumption_reports');
    }
}
