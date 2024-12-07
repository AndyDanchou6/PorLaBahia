<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_method',
        'payment_status',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payments = Payment::where('reservation_id', $payment->reservation_id)->get();

            if ($payments->isEmpty() && $payment->reservation->booking_status == 'on_hold') {
                $payment->reservation->update([
                    'booking_status' => 'active',
                    'on_hold_expiration_date' => null,
                ]);
            }
        });
    }
}
