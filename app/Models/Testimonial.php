<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guest_id',
        'profile_image',
        'comment',
    ];

    public function guest()
    {
        return $this->belongsTo(GuestInfo::class);
    }
}
