<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangePromotionProductPivotUniqueness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_product', function (Blueprint $table) {
            $table->unique(['product_id', 'promotion_id'], 'unique_product_promotion_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $keyExists = DB::select(DB::raw('SHOW KEYS FROM product_author WHERE Key_name=\'unique_product_promotion_index\''));

        if ($keyExists) {
            Schema::table('product_author', function (Blueprint $table) {
                $table->dropUnique('unique_product_promotion_index');
            });
        }
    }
}
