<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIndexToProdMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $schemaManager->listTableIndexes('product_movements');

            if (! array_key_exists('product_movements_causer_id_index', $indexesFound)) {
                $table->index('causer_id', 'product_movements_causer_id_index');
            }
            if (! array_key_exists('product_movements_source_id_index', $indexesFound)) {
                $table->index('source_id', 'product_movements_source_id_index');
            }
            if (! array_key_exists('product_movements_destination_id_index', $indexesFound)) {
                $table->index('destination_id', 'product_movements_destination_id_index');
            }
            if (! array_key_exists('product_movements_comment_general_index', $indexesFound)) {
                $table->index([DB::raw('comment_general(50)')], 'product_movements_comment_general_index');
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
        Schema::table('prod_movements', function (Blueprint $table) {
            $table->dropIndex('product_movements_causer_id_index');
            $table->dropIndex('product_movements_source_id_index');
            $table->dropIndex('product_movements_destination_id_index');
            $table->dropIndex('product_movements_comment_general_index');
        });
    }
}
