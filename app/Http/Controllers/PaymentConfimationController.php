<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Events\smsNotification;

class PaymentConfimationController extends Controller
{
    public function confirm(String $id)
    {
        $checkPayment = Payment::where('reservation_id', $id)->whereNotIn('payment_status', ['void', 'unpaid'])->sum('amount');
        $reservationId = Reservation::where('id', $id)->first();

        if ($checkPayment == $reservationId->booking_fee) {
            $bookingNumber = $reservationId->booking_reference_no;
            $contactNo = $reservationId->guest->contact_no;

            $params = [
                'apikey' => env('SMS_API_KEY'),
                'sender_name' => 'MLGDEV',
                'message' => "Mabuhay! Welcome to Por La Bahia Resort! Thank you for booking with us. Your payment has been confirmed, and your booking reference number is #$bookingNumber. See you soon!",
                'number' => $contactNo,
            ];

            // Http::asForm()->post('https://api.semaphore.co/api/v4/messages', $params);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed and SMS sent!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment does not match the required booking fee.',
            ]);
        }
    }
}
