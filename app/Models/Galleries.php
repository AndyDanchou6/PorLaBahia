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
        'gallery_type',
        'gallery_id'
    ];

    protected $casts = [
        'image' => 'json'
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
        }

        return null;
    }
}