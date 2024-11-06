<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationPromo extends Model
{
    use HasFactory;

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
}
