<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentConfimationController extends Controller
{
    public function confirm(String $id)
    {
        $checkPayment = Payment::where('reservation_id', $id)->whereNotIn('payment_status', ['void', 'unpaid'])->sum('amount');
        $reservationId = Reservation::where('id', $id)->first();

        if ($checkPayment == $reservationId->booking_fee) {
            // $number = $reservationId->booking_reference_no;

            // $params = [
            //     'apikey' => env('SMS_API_KEY'),
            //     'sender_name' => 'MLGDEV',
            //     'message' => "Your payment has been confirmed, your booking #$number",
            //     'number' => $reservationId->guest->contact_no,
            // ];

            // // dd($params);
            // $msg = Http::asForm()->post('https://api.semaphore.co/api/v4/messages', $params);

            // dd($msg->object());

            dd(true);
        } else {
            dd(false);
        }
    }
}
