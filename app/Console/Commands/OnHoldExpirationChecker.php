<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OnHoldExpirationChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:on_hold-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing status to expired for on hold bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->startOfMinute();

        $expiredReservations = Reservation::where('on_hold_expiration_date', $now)
            ->where(function ($query) {
                $query->where('booking_status', 'on_hold')
                    ->orWhere('booking_status', 'pending');
            })
            ->get();

        if ($expiredReservations->count() > 0) {
            foreach ($expiredReservations as $expired) {
                $reservationStatus = 'Unknown Status';

                if ($expired->booking_status == 'pending') {
                    $reservationStatus = 'Pending';
                } elseif ($expired->booking_status == 'on_hold') {
                    $reservationStatus = 'On Hold';
                }

                $expired->booking_status = 'expired';
                $expired->save();

                $checkIn = Carbon::parse($expired->check_in_date)->startOfDay();
                $today = Carbon::today();

                $payments = \App\Models\Payment::where('reservation_id', $expired->id)
                    ->where('payment_status', 'paid')
                    ->get();

                $creditable = $today->diffInDays($checkIn);
                $creditAmount = 0;
                $bookingSuffix = substr($expired->booking_reference_no, 13);
                $expirationDate = Carbon::now()->addYear();
                $coupon = \App\Models\GuestCredit::generateCoupon($bookingSuffix);

                // void payments and record creditable
                if ($payments->count() > 0 && $creditable > 10) {
                    foreach ($payments as $payment) {
                        $creditAmount += $payment->amount;
                        $payment->update([
                            'payment_status' => 'void'
                        ]);
                    }
                }

                $guestCredit = \App\Models\GuestCredit::create([
                    'guest_id' => $expired->guest_id,
                    'coupon' => $coupon,
                    'amount' => $creditAmount,
                    'status' => 'active',
                    'expiration_date' => $expirationDate,
                ]);

                Log::info("Expired reservation updated: ID {$expired->booking_reference_no}, Status: {$expired->booking_status}, Credit: {$creditAmount}");

                // Send notification about expiration whether they have credits or not
                $admins = \App\Models\User::where('role', 1)
                    ->get();

                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        if ($creditAmount > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Payment Credited')
                                ->body("$reservationStatus booking $expired->booking_reference_no has expired. PHP $creditAmount payment amount had been credited. Credit coupon: $coupon")
                                ->icon('heroicon-o-archive-box-x-mark')
                                ->iconColor('danger')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->url(\App\Filament\Resources\ReservationResource::getUrl('view', ['record' => $expired->id])),
                                ])
                                ->sendToDatabase($admin)
                                ->broadcast($admin);
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Booking Expired')
                                ->body("$reservationStatus booking $expired->booking_reference_no has expired.")
                                ->icon('heroicon-o-archive-box-x-mark')
                                ->iconColor('danger')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->url(\App\Filament\Resources\ReservationResource::getUrl('view', ['record' => $expired->id])),
                                ])
                                ->sendToDatabase($admin)
                                ->broadcast($admin);
                        }
                    }
                }
            }
        } else {
            Log::info("No expired bookings today");
        }
    }
}
