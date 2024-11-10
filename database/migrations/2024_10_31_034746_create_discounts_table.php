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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code');
            $table->string('description_code');
            $table->string('description');
            $table->string('discount_type');
            $table->integer('value');
            $table->date('expiration_date');
            $table->boolean('usage_limit')->nullable();
            $table->bigInteger('minimum_order')->nullable();
            $table->bigInteger('maximum_order')->nullable();
            $table->boolean('stacking_restriction')->nullable();
            $table->string('applicability')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
