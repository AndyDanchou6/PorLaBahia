<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCredit extends Model
{
    use HasFactory;

    protected $table = 'guest_credits';

    protected $fillable = [
        'guest_id',
        'amount',
        'is_redeemed',
        'date_redeemed',
        'expiration_date',
        'status',
    ];

    public function guest()
    {
        return $this->belongsTo(GuestInfo::class);
    }
}