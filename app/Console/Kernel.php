<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DatabaseBackUp'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // drive backup
        $schedule->command('backup:schedule')
        ->dailyAt(env('DAILY_BACKUP_TIME'))
        ->timezone('Asia/Dhaka');
        // text ----
        //  $schedule->command('inspire')
        // ->everyFiveMinutes()
        // ->timezone('Asia/Dhaka')
        // ->runInBackground()
        // ->appendOutputTo(public_path('file.txt'));
        // $schedule->command('database:backup')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
