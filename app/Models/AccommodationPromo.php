<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationPromo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accommodation_promos';

    protected $fillable = [
        'accommodation_id',
        'discount_type',
        'value',
        'discounted_price',
        'promo_start_date',
        'promo_end_date',
        'status',
    ];

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public static function calculateDiscountedPrice($value, $accommodationId)
    {
        $accommodation = \App\Models\Accommodation::find($accommodationId);

        if ($accommodation) {
            $weekday_price = $accommodation->weekday_price;
            $weekend_price = $accommodation->weekend_price;

            if (Carbon::now()->isWeekday()) {
                $discounted_price = max($weekday_price - ($weekday_price * $value / 100), 0);
            } elseif (Carbon::now()->isWeekend()) {
                $discounted_price = max($weekend_price - ($weekend_price * $value / 100), 0);
            }
            return $discounted_price;
        }

        return null;
    }

    public function getPromotionDateAttribute()
    {
        $promo_start_date = Carbon::parse($this->promo_start_date)->format('F d, Y');
        $promo_end_date = Carbon::parse($this->promo_end_date)->format('F d, Y');

        $promo_date = $promo_start_date . " - " . $promo_end_date;
        if (!$promo_date) {
            return null;
        }
        return $promo_date;
    }
}
