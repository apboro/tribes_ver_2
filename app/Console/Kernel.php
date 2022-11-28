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
        $schedule->command('check:tariff')->everyMinute()->timezone('Europe/Moscow')->appendOutputTo(storage_path('logs/checktariff.log'));
        env('USE_TRIAL_PERIOD', true) ?
            $schedule->command('check:trial')->everyMinute()->timezone('Europe/Moscow')
            : null;
        $schedule->command('tariff:decrement')->dailyAt('23:59')->timezone('Europe/Moscow');
        $schedule->command('send:deactivated:tariff')->dailyAt('10:00')->timezone('Europe/Moscow');
        $schedule->command('send:subscription')->dailyAt('10:30')->timezone('Europe/Moscow');

        $schedule->command('check:admin')->dailyAt('23:59')->timezone('Europe/Moscow');
        $schedule->command('calculate:utility')->everyFiveMinutes()->timezone('Europe/Moscow');
        $schedule->command('check:new_subs')->hourly()->timezone('Europe/Moscow');
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
