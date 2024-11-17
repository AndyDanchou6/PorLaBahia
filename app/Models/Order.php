<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'item',
        'quantity',
        'price',
        'order_date',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class)->withTrashed();
    }
}
