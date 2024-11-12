<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accommodation_id',
        'guest_id',
        'discount_id',
        'booking_reference_no',
        'check_in_date',
        'check_out_date',
        'booking_fee',
        'total_price',
        'payment_method',
        'payment_status',
        'booking_status',
    ];

    public function accommodation() {
        return $this->belongsTo(Accommodation::class);
    }

    public function guest() {
        return $this->belongsTo(GuestInfo::class);
    }

    public function discount() {
        return $this->belongsTo(Discount::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function fees() {
        return $this->hasMany(Fee::class);
    }

    
    protected static function booted()
    {
        static::deleting(function ($reservation) {
            $reservation->orders()->update(['deleted_at' => now()]);
            $reservation->fees()->update(['deleted_at' => now()]);
        });
    }
    
    public function generateBookingReference(int $length = 10): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = '';

        for ($i = 0; $i < $length; $i++) {
            $reference .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $bookingReference = 'PORLABAHIA-' . $reference;

        while (self::where('booking_reference_no', $bookingReference)->exists()) {
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $bookingReference = 'PORLABAHIA-' . $reference;
        }

        return $bookingReference;
    }
}
