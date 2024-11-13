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
        // Gửi email nhắc nhở check-in lúc 7:00 sáng
        $schedule->command('app:send-mail-check-in')->dailyAt('07:00');

        // Gửi email nhắc nhở check-out lúc 17:00 chiều
        $schedule->command('app:send-mail-check-out')->dailyAt('17:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
