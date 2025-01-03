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
        'weekday_promo_price',
        'weekend_promo_price',
        'promo_start_date',
        'promo_end_date',
        'featured_image_promo',
        'status',
    ];

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
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
