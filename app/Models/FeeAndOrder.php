<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeAndOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fees_and_orders';

    protected $fillable = [
        'reservation_id',
        'user_id',
        'category',
        'item',
        'quantity',
        'price',
        'fee_name',
        'charge',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'charge' => 'decimal:2',
    ];
    

    public function reservation()
    {
        return $this->belongsTo(Reservation::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }
}
