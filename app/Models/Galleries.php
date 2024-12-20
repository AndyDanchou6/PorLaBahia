<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galleries extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'galleries_type',
        'galleries_id',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function gallery()
    {
        return $this->morphTo()->withTrashed();
    }

    public function getCategoryNameAttribute()
    {
        if ($this->gallery_type === 'App\Models\Amenities') {
            return 'Amenities';
        } elseif ($this->gallery_type === 'App\Models\Accommodation') {
            return 'Accommodation';
        } elseif ($this->gallery_type === 'App\Models\RestaurantMenu') {
            return 'Restaurant Menu';
        }

        return null;
    }

    public function getSelectedNameAttribute()
    {
        if ($this->gallery_type === 'App\Models\Amenities') {
            $amenity = Amenities::find($this->gallery_id);
            if ($amenity) {
                return $amenity->amenity_name;
            }
        } elseif ($this->gallery_type === 'App\Models\Accommodation') {
            $accommodation = Accommodation::find($this->gallery_id);
            if ($accommodation) {
                return $accommodation->room_name;
            }
        } elseif ($this->gallery_type === 'App\Models\RestaurantMenu') {
            $restaurantMenu = RestaurantMenu::find($this->gallery_id);
            if ($restaurantMenu) {
                return $restaurantMenu->name;
            }
        }

        return null;
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['image'] = json_encode(
            collect($value)->pluck('image')->toArray()
        );
    }
}
