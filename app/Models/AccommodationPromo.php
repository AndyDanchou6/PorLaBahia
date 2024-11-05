<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
