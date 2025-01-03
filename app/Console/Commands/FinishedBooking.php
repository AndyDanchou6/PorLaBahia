<?php

namespace App\Console\Commands;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FinishedBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:finished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change booking status to finished';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $finishedBookings = Reservation::where('booking_status', 'active')
            ->whereDate('check_out_date', $today)
            ->get();

        if ($finishedBookings->count() > 0) {
            foreach ($finishedBookings as $booking) {
                $booking->update([
                    'booking_status' => 'finished',
                ]);

                $guest = $booking->guest->fullname;

                Log::info("$guest had checked out their booking $booking->booking_reference_no today.");

                $admins = \App\Models\User::where('role', 1)
                    ->get();

                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        \Filament\Notifications\Notification::make()
                            ->title('Guest has checked out')
                            ->body("$guest had checked out their booking $booking->booking_reference_no today.")
                            ->icon('heroicon-o-user')
                            ->iconColor('success')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->url(ReservationResource::getUrl('view', ['record' => $booking->id])),
                            ])
                            ->sendToDatabase($admin)
                            ->broadcast($admin);
                    }
                }
            }
        } else {
            Log::info("No guest has checked out today");
        }
    }
}
