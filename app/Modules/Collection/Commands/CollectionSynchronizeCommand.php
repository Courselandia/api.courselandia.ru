<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Commands;

use Log;
use Illuminate\Console\Command;
use App\Modules\Collection\Sync\Synchronize;

/**
 * Синхронизация курсов коллекций.
 */
class CollectionSynchronizeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'collection:synchronize';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Проход по всем активным коллекциям и синхронизирует их курсы.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Начинаем синхронизацию...');

        $synchronize = new Synchronize();
        $bar = $this->output->createProgressBar($synchronize->getTotal());
        $bar->start();

        $synchronize->addEvent('sync', function () use ($bar) {
            $bar->advance();
        });

        $synchronize->run();
        $bar->finish();

        $this->info("\n\nСинхронизация была завершена.");
        Log::info('Синхронизация коллекций.');
    }
}
