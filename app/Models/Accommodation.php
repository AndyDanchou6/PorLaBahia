<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accommodations';

    protected $fillable = [
        'room_name',
        'description',
        'free_pax',
        'excess_pax_price',
        'weekday_price',
        'weekend_price',
        'booking_fee',
        'main_image',
    ];

    protected $casts = [
        'free_pax' => 'integer',
        'excess_pax_price' => 'decimal:2',
        'weekday_price' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'booking_fee' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->hasMany(Reservation::class);
    }

    public function Galleries()
    {
        return $this->morphMany(Galleries::class, 'galleries');
    }

    public function accommodation_promo()
    {
        return $this->hasMany(AccommodationPromo::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($accommodation) {
            $accommodation->galleries()->each(function ($gallery) {
                $gallery->forceDelete();
            });
        });
    }
}
