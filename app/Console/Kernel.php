<?php

namespace App\Console;

use OAuth;
use Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Modules\User\Models\UserAuth;
use App\Modules\Task\Models\Task;

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
        $schedule->exec('rm -r ' . storage_path('app/tmp/*'))->weekly();
        $schedule->exec(
            'mysqlcheck --user=' . Config::get('database.connections.mysql.username') . ' --password=' . Config::get(
                'database.connections.mysql.password'
            ) . ' --optimize ' . Config::get('database.connections.mysql.database')
        )->weekly();

        $schedule->call(function () {
            OAuth::clean();
        })->daily();

        $schedule->command('model:prune', [
            '--model' => [Task::class, UserAuth::class],
        ])->daily();

        $schedule->command('course:import')->dailyAt('00:00');
        $schedule->command('promotion:import')->dailyAt('01:00');
        $schedule->command('promocode:import')->dailyAt('01:15');
        // $schedule->command('article:write')->dailyAt('02:00');
        $schedule->command('metatag:apply --update')->dailyAt('01:00');
        $schedule->command('course:normalize')->dailyAt('05:00');
        $schedule->command('collection:synchronize')->dailyAt('06:00');
        $schedule->command('teacher:normalize')->dailyAt('06:30');
        $schedule->command('course:json')->twiceDaily(7, 19);
        $schedule->command('sitemap:generate')->dailyAt('13:00');
        $schedule->command('course:yml')->dailyAt('13:00');
        // $schedule->command('crawl:push')->dailyAt('16:00');
        // $schedule->command('crawl:check')->dailyAt('17:00');
        $schedule->command('school:count-amount-courses')->dailyAt('08:00');
        $schedule->command('school:count-amount-teachers')->dailyAt('08:05');
        $schedule->command('school:count-amount-reviews')->dailyAt('08:10');
        $schedule->command('review:import')->weekly();
        $schedule->command('school:count-rating')->dailyAt('09:00');
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
