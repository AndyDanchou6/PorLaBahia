<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingReminderController extends Controller
{
    public function sendReminder()
    {
        $today = Carbon::today()->toDateString();

        $reservationsToRemind = \App\Models\Reservation::whereRaw("DATEDIFF(check_in_date, ?) = ?", [$today, 15])
            ->get();

        if (!empty($reservationsToRemind)) {
            foreach ($reservationsToRemind as $reservation) {
                $bookingNumber = $reservation->booking_reference_no;
                $contactNo = $reservation->guest->contact_no;
                $checkIn = Carbon::parse($reservation->check_in_date)->format('M d, Y');
                $checkOut = Carbon::parse($reservation->check_out_date)->format('M d, Y');

                $params = [
                    'apikey' => env('SMS_API_KEY'),
                    'sender_name' => 'MLGDEV',
                    'message' => "Mabuhay! Welcome to Por La Bahia Resort! Your booking #$bookingNumber is fast approaching, on $checkIn to $checkOut. Thank you for booking with us. See you soon!",
                    'number' => $contactNo,
                ];

                // Http::asForm()->post('https://api.semaphore.co/api/v4/messages', $params);

                return response()->json([
                    'message' => 'SMS reminder sent successfully!',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No guest to remind',
            ], 404);
        }
    }
}
