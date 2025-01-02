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
        Schema::table('accommodation_promos', function (Blueprint $table) {
            $table->dropColumn('discounted_price');
            $table->decimal('weekday_promo_price', 10, 2)->after('value');
            $table->decimal('weekend_promo_price', 10, 2)->after('weekday_promo_price');
            $table->string('featured_image_promo')->nullable()->after('weekend_promo_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodation_promos', function (Blueprint $table) {
            $table->dropColumn('weekday_promo_price');
            $table->dropColumn('weekend_promo_price');
            $table->string('featured_image_promo');
            $table->decimal('discounted_price', 10, 2)->after('value');
        });
    }
};
