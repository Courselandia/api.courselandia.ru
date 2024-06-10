<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Commands;

use Log;
use App\Modules\Crawl\Plan\Plan;
use Illuminate\Console\Command;

/**
 * Планирование индексирования страниц.
 */
class PlanCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'crawl:plan';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск планирования индексации.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $plan = new Plan();
        $total = $plan->total();

        if ($total) {
            $this->line('Запуск заданий на планирование индексации...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $plan->addEvent('created', function () use ($bar) {
                $bar->advance();
            });

            $plan->start();
            $bar->finish();

            $this->info("\n\nЗадания на планирование индексации было выполнено.");
        } else {
            $this->info("\n\nНет страниц для поанирования индексации.");
        }

        Log::info('Запуск заданий на планирование индексации.');
    }
}
