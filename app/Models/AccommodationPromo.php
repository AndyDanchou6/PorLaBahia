<?php

namespace App\Models;

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
    ];

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public static function calculateDiscountedPrice($value, $discountType, $accommodationId)
    {
        $accommodation = \App\Models\Accommodation::find($accommodationId);

        if ($accommodation) {
            $price = $accommodation->price;

            if ($discountType === 'fixed') {
                return max($price - $value, 0);
            } elseif ($discountType === 'percentage') {
                return max($price - ($price * $value / 100), 0);
            }

            return $price;
        }

        return null;
    }
}
