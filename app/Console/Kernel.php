<?php

namespace App\Console;

use App\Services\BackupsService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
        // $schedule->command('inspire')->hourly();
    // }
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $adminController = new \App\Http\Controllers\DashboardController();
            $adminController->resetSerialNumbers();
        })->yearly();
        // $schedule->command('backup:upload')->everyThreeHours();
        // $schedule->call(function () {
        //     app(BackupsService::class)->takeBackupIfDue();
        // })->everyMinute();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
