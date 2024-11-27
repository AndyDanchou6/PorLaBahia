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
        'booking_reference_no',
        'check_in_date',
        'check_out_date',
        'total_paid',
        'total_payable',
        'balance',
        'booking_status',
    ];

    protected $casts = [
        'total_paid' => 'decimal:2',
        'total_price' => 'decimal:2',
        'balance' => 'decimal:2',
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

    public function feesAndOrders() {
        return $this->hasMany(FeeAndOrder::class);
    }

    
    protected static function booted()
    {
        static::deleting(function ($reservation) {
            // $reservation->orders()->update(['deleted_at' => now()]);
            // $reservation->fees()->update(['deleted_at' => now()]);
            $reservation->feesAndOrders()->update(['deleted_at' => now()]);
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
