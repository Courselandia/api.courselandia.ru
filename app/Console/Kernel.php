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
        $schedule->exec('rm -r '.storage_path('app/tmp/*'))->daily();
        $schedule->exec(
            'mysqlcheck --user='.Config::get('database.connections.mysql.username').' --password='.Config::get(
                'database.connections.mysql.password'
            ).' --optimize '.Config::get('database.connections.mysql.database')
        )
            ->weekly();

        $schedule->call(function () {
            OAuth::clean();
        })->daily();
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
