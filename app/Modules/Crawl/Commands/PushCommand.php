<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Commands;

use Log;
use App\Modules\Crawl\Push\Push;
use Illuminate\Console\Command;

/**
 * Отправка страниц на индексацию в поисковые системы.
 */
class PushCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'crawl:push';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск отправки страниц на индексацию в поисковые системы.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $push = new Push();
        $total = $push->total();

        if ($total) {
            $this->line('Запуск заданий на индексацию страниц...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $push->addEvent('pushed', function () use ($bar) {
                $bar->advance();
            });

            $push->run();
            $bar->finish();

            $this->info("\n\nЗадания на индексацию страниц были запущены.");
        } else {
            $this->info("\n\nНет страниц на индексацию.");
        }

        Log::info('Запуск заданий на индексацию страниц.');
    }
}
