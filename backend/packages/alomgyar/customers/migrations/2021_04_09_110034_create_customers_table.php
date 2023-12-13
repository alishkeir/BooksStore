<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone');
            $table->boolean('author_follow_up')->default(0);
            $table->boolean('comment_follow_up')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('store')->comment('0 - álomgyár, 1 - olcsókönyvek, 2 - nagyker');
            $table->string('remember_token', 100)->default(0);
            $table->timestamps();
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 100)->nullable();
            $table->json('last_login_device')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
