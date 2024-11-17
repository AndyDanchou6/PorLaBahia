<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenities extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'main_image',
        'amenity_name',
        'description',
    ];

    public function galleries()
    {
        return $this->morphMany(Galleries::class, 'gallery');
    }

    public static function boot()
    {
        parent::boot();

        // static::deleting(function ($amenties) {
        //     $amenties->galleries()->each(function ($gallery) {
        //         $gallery->delete();
        //     });
        // });

        static::deleting(function ($amenity) {
            $amenity->galleries()->each(function ($gallery) {
                $gallery->forceDelete();
            });
        });
    }
}
