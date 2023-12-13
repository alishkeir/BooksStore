<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDibookIdToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['mobi_url', 'mobi_size', 'epub_url', 'epub_size']);
            $table->integer('dibook_id')->nullable();
            $table->boolean('dibook_sync')->nullable();
            $table->integer('book24_id')->nullable();
            $table->boolean('book24_sync')->nullable();
            $table->boolean('is_dependable_status')->default(0);
            $table->boolean('is_stock_sensitive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['dibook_id', 'dibook_sync', 'book24_id', 'book24_sync', 'is_dependable_status', 'is_stock_sensitive']);
            $table->string('mobi_url')->nullable();
            $table->string('mobi_size')->nullable();
            $table->string('epub_url')->nullable();
            $table->string('epub_size')->nullable();
        });
    }
}
