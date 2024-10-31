<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_no',
        'email',
        'address',
        'fb_name',
    ];
}
