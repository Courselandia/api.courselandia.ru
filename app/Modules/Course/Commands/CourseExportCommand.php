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
use App\Modules\Course\DbFile\Export;

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
    protected $description = 'Экспортирование курсов в файлы для быстрой загрузки.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Экспортирование курсов с источников...');

        $export = new Export();
        $bar = $this->output->createProgressBar($export->getTotal());
        $bar->start();

        $export->addEvent('read', function () use ($bar) {
            $bar->advance();
        });

        $export->run();
        $bar->finish();

        if ($export->hasError()) {
            $errors = $export->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка экспорта курсов: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nИмпорт курсов завершен.");
        Log::info('Импорт курсов в файлы.');
    }
}
