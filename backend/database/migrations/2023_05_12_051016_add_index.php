<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $schemaManager->listTableIndexes('orders');

            if (! array_key_exists('orders_created_at_index', $indexesFound)) {
                $table->index('created_at', 'orders_created_at_index');
            }
            if (! array_key_exists('orders_updated_at_index', $indexesFound)) {
                $table->index('updated_at', 'orders_updated_at_index');
            }
        });
        Schema::table('addresses', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $schemaManager->listTableIndexes('addresses');

            if (! array_key_exists('addresses_role_id_index', $indexesFound)) {
                $table->index('role_id', 'addresses_role_id_index');
            }
            if (! array_key_exists('addresses_last_name_index', $indexesFound)) {
                $table->index('last_name', 'addresses_last_name_index');
            }
            if (! array_key_exists('addresses_first_name_index', $indexesFound)) {
                $table->index('first_name', 'addresses_first_name_index');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_created_at_index');
            $table->dropIndex('orders_updated_at_index');
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex('addresses_role_id_index');
            $table->dropIndex('addresses_last_name_index');
            $table->dropIndex('addresses_first_name_index');
        });
    }
}
