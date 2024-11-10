<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

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
    ];

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
        return $this->hasOne(Order::class);
    }
}
