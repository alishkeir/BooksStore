<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAuthorMailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('customer_author_mails', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('author_id');
            $table->integer('product_id');

            $table->timestamps();

            $table->unique(['customer_id', 'author_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_author_mails');
    }
}
