<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAuthorProductPivotUniqueness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_author', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->unique(['product_id', 'author_id'], 'unique_product_author_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_author', function (Blueprint $table) {
            $table->dropUnique('unique_product_author_index');
        });
    }
}
