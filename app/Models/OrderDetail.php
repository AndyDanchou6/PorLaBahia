<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item',
        'quantity',
        'price',
        'order_date',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
