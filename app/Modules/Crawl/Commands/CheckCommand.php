<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Commands;

use Log;
use App\Modules\Crawl\Check\Check;
use Illuminate\Console\Command;

/**
 * Проверка страниц на индексацию в поисковых системах.
 */
class CheckCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'crawl:check';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск проверки страниц на индексацию в поисковых системах.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $check = new Check();
        $total = $check->total();

        if ($total) {
            $this->line('Запуск заданий на проверку индексации...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $check->addEvent('checked', function () use ($bar) {
                $bar->advance();
            });

            $check->run();
            $bar->finish();

            $this->info("\n\nЗадания на проверку индексации страниц были запущены.");
        } else {
            $this->info("\n\nНет страниц для проверки.");
        }

        Log::info('Запуск заданий для проверки индексации страниц.');
    }
}
