<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_no',
        'email',
        'address',
        'fb_name',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        $full_name = $this->first_name . ' ' . $this->last_name;
        return $full_name;
    }

    // public function getFullNamesAttribute()
    // {
    //     return "{$this->first_name} {$this->last_name}";
    // }

    public function guestCredit()
    {
        return $this->hasMany(GuestCredit::class, 'guest_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}
