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
    protected $signature = 'on-hold:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing status to expired for on hold bookings after 12 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->startOfMinute();

        $expiredReservations = Reservation::where('on_hold_expiration_date', $now)
            ->get();

        if (!$expiredReservations->isEmpty()) {
            foreach ($expiredReservations as $expired) {
                $expired->booking_status = 'expired';
                $expired->save(); 

                Log::info("Expired reservation updated: ID {$expired->id}, Status: {$expired->booking_status}");
            }
        } else {
            Log::info("No expired reservations found as of {$now}.");
        }
    }
}
