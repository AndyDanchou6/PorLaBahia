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
        Schema::create('guest_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->decimal('amount', 10, 2)->nullable()->default(0);
            $table->boolean('is_redeemed')->nullable()->default(false);
            $table->string('date_redeemed')->nullable();
            $table->string('expiration_date');
            $table->string('status');
            $table->timestamps();

            $table->foreign('guest_id')
            ->references('id')
            ->on('guest_infos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_credits');
    }
};
