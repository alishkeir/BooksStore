<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRecommendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommenders', function (Blueprint $table) {
            $table->integer('original_product_id');
            $table->integer('promoted_product_id');

            $table->string('subject')->nullable()->default(null);
            $table->text('message_body')->nullable()->default(null);

            $table->timestamp('release_date')->nullable()->default(null);
            $table->timestamp('released_at')->default(null)->nullable();
        });
    }
}
