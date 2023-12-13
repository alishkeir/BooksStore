<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
            $table->string('reference_nr')->unique()->after('id');
            $table->text('comment_void')->nullable()->after('destination_id');
            $table->text('comment_general')->nullable()->after('comment_void');

            $table->dropForeign('product_movements_product_id_foreign');
            $table->dropColumn('product_id');
            $table->dropColumn('stock_in');
            $table->dropColumn('status');
            $table->dropColumn('stock_out');
        });

        Schema::create('product_movements_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_movements_id')->constrained();
            $table->foreignId('product_id')->constrained('product');
            $table->unsignedInteger('stock_in');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - új bevételezett, 2 - ebből fogy, 3 - elfogyott');
            $table->integer('stock_out')->default(0);
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
        Schema::table('product_movements', function (Blueprint $table) {
            $table->json('purchase_price')->nullable();
            $table->dropColumn('reference_nr');
            $table->dropColumn('comment_void');
            $table->dropColumn('comment_general');

            $table->foreignId('product_id')->constrained('product');
            $table->unsignedInteger('stock_in');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - új bevételezett, 2 - ebből fogy, 3 - elfogyott');
            $table->integer('stock_out')->default(0);
        });

        Schema::dropIfExists('product_movements_items');
    }
}
