<?php

namespace App\Console\Commands;

use App\Models\AccommodationPromo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredPromos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotion:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update status as expired if their promo end date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $expiredPromos = AccommodationPromo::where('promo_end_date', '<', $today->startOfDay());

        $output = $expiredPromos->update([
            'status' => 'expired'
        ]);

        $this->info("Number {$output} promo have been marked as expired.");

        return $output;
    }
}
