<?php

namespace App\Console;

use OAuth;
use Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->exec('rm -r ' . storage_path('app/tmp/*'))->daily();
        $schedule->exec(
            'mysqlcheck --user=' . Config::get('database.connections.mysql.username') . ' --password=' . Config::get(
                'database.connections.mysql.password'
            ) . ' --optimize ' . Config::get('database.connections.mysql.database')
        )->weekly();

        $schedule->call(function () {
            OAuth::clean();
        })->daily();

        $schedule->command('course:import')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
