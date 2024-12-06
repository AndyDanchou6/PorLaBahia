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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id');
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('booking_reference_no');
            $table->string('check_in_date');
            $table->string('check_out_date');
            $table->decimal('booking_fee', 10, 2)->default(0.0);
            $table->string('booking_status');
            $table->string('on_hold_expiration_date')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('accommodation_id')
            ->references('id')
            ->on('accommodations');
            $table->foreign('guest_id')
            ->references('id')
            ->on('guest_infos');
            $table->foreign('discount_id')
            ->references('id')
            ->on('discounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
