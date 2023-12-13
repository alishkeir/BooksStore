<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentBottomToProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->text('comment_bottom')->nullable()->after('comment_general');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->dropColumn('comment_bottom');
        });
    }
}
