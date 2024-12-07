<?php

namespace App\Models;

use Carbon\Carbon;
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
        'booking_status',
        'on_hold_expiration_date',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'booking_fee' => 'decimal:2',
    ];


    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function guest()
    {
        return $this->belongsTo(GuestInfo::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected static function booted()
    {
        // static::deleting(function ($reservation) {
        //     $reservation->appliedDiscount()->update(['deleted_at' => now()]);
        // });
        static::updating(function ($reservation) {
            if ($reservation->isDirty('booking_status') && $reservation->booking_status === 'cancelled') {

                $guestCredits = $reservation->guest->guestCredit->first();

                if ($guestCredits) {
                    $existingBookingIds = $guestCredits->booking_ids ?? [];

                    if (!in_array($reservation->id, $existingBookingIds)) {

                        $existingBookingIds[] = $reservation->id;
                        $newAmount = $guestCredits->amount + $reservation->booking_fee;

                        $guestCredits->update([
                            'booking_ids' => $existingBookingIds,
                            'amount' => $newAmount,
                        ]);
                    }
                } else {
                    GuestCredit::create([
                        'guest_id' => $reservation->guest_id,
                        'booking_ids' => [$reservation->id],
                        'amount' => $reservation->booking_fee,
                        'expiration_date' => Carbon::now()->addYear(),
                        'status' => 'active',
                    ]);
                }
            }
        });

        static::creating(function ($reservation) {
            if (!$reservation->booking_status) {
                $reservation->booking_status = 'on_hold';
            }

            if ($reservation->booking_status == 'on_hold') {
                $reservation->on_hold_expiration_date = Carbon::now()->addHours(12);
            }
        });
    }

    public function generateBookingReference(int $length = 4): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = '';

        for ($i = 0; $i < $length; $i++) {
            $reference .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $year = Carbon::today()->year;
        $month = Carbon::today()->month;
        $day = Carbon::today()->day;

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $bookingReference = 'PLB-' . $year . $month . $day . '-' . $reference;

        while (self::where('booking_reference_no', $bookingReference)->exists()) {
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $bookingReference = 'PLB-' . $year . $month . $day . '-' . $reference;
        }

        return $bookingReference;
    }
}
