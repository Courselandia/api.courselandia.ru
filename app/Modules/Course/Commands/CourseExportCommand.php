<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use Log;
use Illuminate\Console\Command;
use App\Modules\Course\Export\Export;

/**
 * Экспорт курсов в файлы для их быстрой загрузки.
 */
class CourseExportCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:export';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Экспортирование курсов в MongoDb для быстрой загрузки.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Создаем очереди на отправку в экспорт...');

        $export = new Export();
        $bar = $this->output->createProgressBar($export->getTotal());
        $bar->start();

        $export->addEvent('export', function () use ($bar) {
            $bar->advance();
        });

        $export->run();
        $bar->finish();

        $this->info("\n\nЭкспорт был отправлен в очереди.");
        Log::info('Отправка экспорта курсов в очереди.');
    }
}
