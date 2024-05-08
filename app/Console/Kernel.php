<?php

namespace App\Console;

use App\Jobs\postScrapingData;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('ScrapSitesData:command')->everyMinute();
        $schedule->command('PostData:command')->everyMinute();
        $schedule->command('scheduler:post')->everyMinute();
        $schedule->call(function () {
            Artisan::call('queue:work', [
                '--once' => true,
            ]);
        })->everyMinute();
        $schedule->call(function () {
            Artisan::call('queue:work', [
                '--once' => true,
            ]);
        })->everyMinute();
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
