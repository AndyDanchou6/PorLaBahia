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
            $table->text('title')->nullable();
            $table->longText('value')->nullable();
            $table->json('icons')->nullable();
            $table->boolean('is_published')->nullable()->default(0);
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
