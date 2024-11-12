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
}
