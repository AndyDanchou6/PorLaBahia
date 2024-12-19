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
        Schema::table('guest_credits', function (Blueprint $table) {
            $table->dropColumn('booking_ids');
            $table->string('coupon')->nullable()->after('guest_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_credits', function (Blueprint $table) {
            $table->dropColumn('coupon');
            $table->string('booking_ids')->nullable()->after('guest_id');
        });
    }
};
