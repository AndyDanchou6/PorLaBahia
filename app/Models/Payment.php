<?php

namespace App\Models;

use Carbon\Carbon;
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
        'gcash_reference_number',
        'gcash_screenshot'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            // first payment (reservation fee) changes booking status to active
            // $payments = Payment::where('reservation_id', $payment->reservation_id)->get();

            // if ($payments->isEmpty() && $payment->reservation->booking_status == 'on_hold') {
            //     $payment->reservation->update([
            //         'booking_status' => 'active',
            //         'on_hold_expiration_date' => null,
            //     ]);
            // }

            // if credits is used for payment
            // if ($payment->payment_method === 'credits') {
            //     $guestCredit = GuestCredit::find($payment->reservation->guest_id)->first();

            //     $newCredit = $guestCredit->amount - $payment->amount;
            //     $guestCredit->update([
            //         'amount' => $newCredit
            //     ]);
            // }


            $booking_fee = $payment->reservation->booking_fee;

            $paymentAmount = Payment::where('reservation_id', $payment->reservation_id)
                ->whereNotIn('payment_status', ['void', 'unpaid'])
                ->sum('amount');

            $payments = $paymentAmount + $payment->amount;

            if ($payments == $booking_fee && $payment->payment_status == 'paid') {
                $payment->reservation->update([
                    'booking_status' => 'active',
                    'on_hold_expiration_date' => null,
                ]);
            }

            if ($payment->payment_method == 'cash') {
                $payment->payment_status = 'paid';
            } elseif ($payment->payment_method == 'GCash') {
                $payment->payment_status = 'unpaid';
            }
        });

        static::updating(function ($payment) {
            // $guestCredit = GuestCredit::find($payment->reservation->guest_id)->first();
            // $newCredit = $guestCredit->amount;

            // if ($payment->isDirty('payment_method') && $payment->getOriginal('payment_method') === 'credits') {
            //     $newCredit = $newCredit + $payment->amount;
            // } elseif ($payment->isDirty('payment_method') && $payment->payment_method === 'credits') {
            //     $newCredit = $newCredit - $payment->amount;
            // }

            // $guestCredit->update([
            //     'amount' => $newCredit
            // ]);
        });
    }
}
