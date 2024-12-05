<?php

namespace App\Console\Commands;

use App\Models\AccommodationPromo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PromotionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotion:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Promotion Status based on dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $promoStatus = Carbon::today();

        $expiredPromo = AccommodationPromo::where('promo_end_date', '=', $promoStatus)->update(['status' => 'expired']);
        $incomingPromo = AccommodationPromo::where('promo_start_date', '>', $promoStatus)->update(['status' => 'incoming']);
        $activePromo = AccommodationPromo::where('promo_start_date', '=', $promoStatus)->update(['status' => 'active']);

        $this->info("Promotion Status: Expired: {$expiredPromo}, Incoming: {$incomingPromo}, Active: {$activePromo}");

        return 0;
    }
}
