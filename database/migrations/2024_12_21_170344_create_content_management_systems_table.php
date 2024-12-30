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
        Schema::create('content_management_systems', function (Blueprint $table) {
            $table->id();
            $table->string('page')->nullable();
            $table->integer('section')->nullable();
            $table->string('title_name')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('value')->nullable();
            $table->string('background_image')->nullable();
            $table->json('icons')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_management_systems');
    }
};
