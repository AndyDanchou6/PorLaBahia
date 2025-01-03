<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('promotion:status')->daily()->withoutOverlapping();
        $schedule->command('booking:on_hold-expiration')->everyMinute()->withoutOverlapping();
        $schedule->command('booking:reminder')->daily()->withoutOverlapping();
        $schedule->command('booking:finished')->dailyAt(9)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\PromotionStatus::class,
    ];
}
