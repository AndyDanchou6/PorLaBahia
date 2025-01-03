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
        'payment_type',
    ];

    protected $casts = [
        'booking_fee' => 'decimal:2',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

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

    public function getAvailableAccommodations($checkInDate, $checkOutDate, $record = null)
    {
        $dateFormat = 'M d, Y';
        $accommodations = Accommodation::all();
        $availableAccommodation = [];
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        $bookings = $this->where(function ($query) {
            $query->where('booking_status', '=', 'on_hold')
                ->orWhere('booking_status', '=', 'pending')
                ->orWhere('booking_status', '=', 'active');
        })
            ->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                    ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                    ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                        $query->where('check_in_date', '<', $checkInDate)
                            ->where('check_out_date', '>', $checkOutDate);
                    });
            })
            ->orderBy('check_in_date', 'asc')
            ->get()
            ->groupBy('accommodation_id');

        foreach ($accommodations as $accommodation) {
            if (!isset($bookings[$accommodation->id])) {
                $availableAccommodation[$accommodation->id . '/' . $checkIn . '/' . $checkOut] = $accommodation->room_name . ' available on ' . $checkIn->format($dateFormat) . ' to ' . $checkOut->format($dateFormat);
                continue;
            }

            $bookedAccommodations = $bookings[$accommodation->id];
            $nextCheckIn = $checkIn;

            foreach ($bookedAccommodations as $booked) {
                $bookedCheckIn = Carbon::parse($booked->check_in_date);
                $bookedCheckOut = Carbon::parse($booked->check_out_date);

                if ($record != null && $booked->id === $record) {
                    if ($bookedCheckOut > $checkOut) {
                        $availableAccommodation[$accommodation->id . '/' . $nextCheckIn . '/' . $checkOut] = $accommodation->room_name . ' available on ' . $nextCheckIn->format($dateFormat) . ' to ' . $checkOut->format($dateFormat);
                        $nextCheckIn = $checkOut;

                        continue;
                    } else {
                        $availableAccommodation[$accommodation->id . '/' . $nextCheckIn . '/' . $bookedCheckOut] = $accommodation->room_name . ' available on ' . $nextCheckIn->format($dateFormat) . ' to ' . $bookedCheckOut->format($dateFormat);
                    }
                } elseif ($nextCheckIn->lt($bookedCheckIn)) {
                    $availableAccommodation[$accommodation->id . '/' . $nextCheckIn . '/' . $bookedCheckIn] = $accommodation->room_name . ' available on ' . $nextCheckIn->format($dateFormat) . ' to ' . $bookedCheckIn->format($dateFormat);
                }

                $nextCheckIn = $bookedCheckOut;
            }

            if ($nextCheckIn->lt($checkOut)) {
                $availableAccommodation[$accommodation->id . '/' . $bookedCheckOut . '/' . $checkOut] = $accommodation->room_name . ' available on ' . $bookedCheckOut->format($dateFormat) . ' to ' . $checkOut->format($dateFormat);
            }
        }

        if (!$availableAccommodation) {
            return [
                null => 'No Available Accommodation',
            ];
        }

        // dd($bookings);
        return $availableAccommodation;
    }
}
