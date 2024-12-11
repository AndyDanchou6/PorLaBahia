<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'new_unit',
    ];

    public function menu()
    {
        return $this->hasMany(RestaurantMenu::class, 'unit_id');
    }
}
