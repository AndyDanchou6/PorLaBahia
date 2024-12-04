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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');
            $table->text('description')->nullable();
            $table->integer('free_pax');
            $table->decimal('excess_pax_price', 10, 2)->default(0.0);
            $table->decimal('weekday_price', 10, 2)->default(0.0);
            $table->decimal('weekend_price', 10, 2)->default(0.0);
            $table->decimal('booking_fee', 10, 2)->default(0.0);
            $table->string('main_image');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
