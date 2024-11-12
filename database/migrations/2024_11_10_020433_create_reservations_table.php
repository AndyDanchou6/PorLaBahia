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
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('booking_fee');
            $table->integer('total_price')->nullable();
            $table->string('payment_method');
            $table->string('payment_status');
            $table->boolean('booking_status')->default(true);
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
