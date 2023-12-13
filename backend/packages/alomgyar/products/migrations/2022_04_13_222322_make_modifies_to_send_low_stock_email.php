<?php

use Alomgyar\Templates\Templates;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeModifiesToSendLowStockEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->tinyInteger('low_stock')->default(0);
        });
        Schema::table('warehouse', function (Blueprint $table) {
            $table->string('secondary_type', 255)->nullable()->after('type');
        });

        DB::table('product')->update([
            'low_stock' => 1,
        ]);

        $model = new Templates();
        $model->title = 'Alacsony raktárkészlet';
        $model->subject = 'Alacsony raktárkészlet';
        $model->slug = 'low-stock';
        $model->description = '<p>Kedves admin!</p><p>Tájékoztatunk, hogy az alábbi könyvekből kevesebb, mint %STOCK_LIMIT% van raktáron.</p><p>%PRODUCT_LIST%</p>';
        $model->store = 0;
        $model->status = 1;
        $model->section = 'Email részlet';
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('low_stock');
        });
        Schema::table('warehouse', function (Blueprint $table) {
            $table->dropColumn('secondary_type');
        });

        Templates::where('slug', 'low-stock')->delete();
    }
}
