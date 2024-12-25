<?php

// namespace App\Console;

// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// class Kernel extends ConsoleKernel
// {
//     /**
//      * Define the application's command schedule.
//      */
//     protected function schedule(Schedule $schedule): void
//     {
//         // Gửi email nhắc nhở check-in lúc 7:00 sáng
//         $schedule->command('app:send-mail-check-in')->dailyAt('07:00');

//         // Gửi email nhắc nhở check-out lúc 17:00 chiều
//         $schedule->command('app:send-mail-check-out')->dailyAt('17:00');
//     }

//     /**
//      * Register the commands for the application.
//      */
//     protected function commands(): void
//     {
//         $this->load(__DIR__ . '/Commands');

//         require base_path('routes/console.php');
//     }
// }


namespace App\Console;

use App\Models\UserAttendance;
use App\Models\UserNotificationSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Lấy danh sách tất cả các bản ghi trong UserAttendance
        $attendances = UserNotificationSchedule::get();

        foreach ($attendances as $attendance) {
            if (!empty($attendance->check_in_time) && strtotime($attendance->check_in_time)) {
                dump(date('H:i', strtotime($attendance->check_in_time)));
                $schedule->command("app:send-mail-check-in {$attendance->user_id}")
                    ->dailyAt(date('H:i', strtotime($attendance->check_in_time)));
            }

            if (!empty($attendance->check_out_time) && strtotime($attendance->check_out_time)) {
                $schedule->command("app:send-mail-check-out {$attendance->user_id}")
                    ->dailyAt($attendance->check_out_time);
            }
        }

        $schedule->command('app:computed-salary')->dailyAt("23:50");
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
