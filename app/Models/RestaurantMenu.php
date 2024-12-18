<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'name',
        'price',
        'category',
        'unit_id'
    ];

    public function galleries()
    {
        return $this->morphMany(Galleries::class, 'galleries');
    }

    public function unit()
    {
        return $this->belongsTo(NewUnit::class, 'unit_id');
    }
}
