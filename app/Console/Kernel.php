<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;



class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:donate')->everyMinute()->timezone('Europe/Moscow');
        $schedule->command('check:tariff')->everyMinute()->timezone('Europe/Moscow');
        env('USE_TRIAL_PERIOD', true) ?
            $schedule->command('check:trial')->everyMinute()->timezone('Europe/Moscow')
            : null;
        $schedule->command('tariff:decrement')->dailyAt('23:59')->timezone('Europe/Moscow');
        $schedule->command('send:deactivated:tariff')->dailyAt('10:00')->timezone('Europe/Moscow');

        $schedule->command('get:messages')->everyFifteenMinutes()->timezone('Europe/Moscow');
        $schedule->command('get:reactions')->everyFifteenMinutes()->timezone('Europe/Moscow');
        $schedule->command('get:views')->everyFifteenMinutes()->timezone('Europe/Moscow');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
