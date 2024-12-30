<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminder to guests 15 days prior to their booking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::asForm()->get(route('sms.reminder'));

        Log::info($response);
    }
}
