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
        'weekday_price',
        'weekend_price',
        'main_image',
        'promo_id',
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function galleries()
    {
        return $this->morphMany(Galleries::class, 'gallery');
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
