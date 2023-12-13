<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickUpPointsTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pick_up_points');
        Schema::create('pick_up_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider')->nullable()->default(null);
            $table->string('provider_name')->nullable()->default(null);
            $table->string('provider_id')->nullable()->default(null);
            $table->string('provider_type')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->integer('zip')->default(0);
            $table->decimal('long', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->text('open')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->boolean('status')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pick_up_points');
    }
}
