<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'notes',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function orderDetail() {
        return $this->hasMany(OrderDetail::class);
    }
}
