<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToPp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_price', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $schemaManager->listTableIndexes('product_price');

            if (! array_key_exists('product_price_product_id_foreign', $indexesFound)) {
                $table->dropIndex('product_price_product_id_index');
                $table->foreign('product_id')->references('id')->on('product');
                $table->unsignedBigInteger('product_id')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_price', function (Blueprint $table) {
            //
        });
    }
}
