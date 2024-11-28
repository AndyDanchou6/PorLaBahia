<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fees_and_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('user_id');
            $table->string('category');
            $table->string('item')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('fee_name')->nullable();
            $table->decimal('charge', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('reservation_id')
            ->references('id')
            ->on('reservations');

            $table->foreign('user_id')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_and_orders');
    }
};
