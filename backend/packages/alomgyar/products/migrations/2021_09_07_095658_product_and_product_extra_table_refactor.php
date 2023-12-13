<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductAndProductExtraTableRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('product', function (Blueprint $table) {
                $table->text('description')->nullable()->after('slug');
                $table->string('mobi_url')->nullable();
                $table->string('mobi_size')->nullable();
                $table->string('epub_url')->nullable();
                $table->string('epub_size')->nullable();
                $table->text('authors')->nullable();
            });
        } catch (Exception $e) {
        }
        Schema::dropIfExists('product_extra');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('product_extra', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('mobi_url')->nullable();
            $table->string('mobi_size')->nullable();
            $table->string('epub_url')->nullable();
            $table->string('epub_size')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('mobi_url');
            $table->dropColumn('mobi_size');
            $table->dropColumn('epub_url');
            $table->dropColumn('epub_size');
            $table->dropColumn('authors');
        });
    }
}
