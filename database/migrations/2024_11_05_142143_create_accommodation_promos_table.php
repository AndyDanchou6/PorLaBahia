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
        Schema::create('accommodation_promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id');
            $table->string('discount_type');
            $table->decimal('value', 10, 2);
            $table->integer('discounted_price');
            $table->date('promo_start_date');
            $table->date('promo_end_date');
            $table->string('status')->default('active')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('accommodation_id')
                ->references('id')
                ->on('accommodations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_promos');
    }
};
