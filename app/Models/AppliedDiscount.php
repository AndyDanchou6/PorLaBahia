<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppliedDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'discount_id',
        'notes'
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function discount() {
        return $this->belongsTo(Discount::class);
    }
}
