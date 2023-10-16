<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use Log;
use DOMException;
use Illuminate\Console\Command;
use App\Modules\Course\Yml\Export;

/**
 * Экспорт курсов в файлы YML.
 */
class CourseYmlCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:yml';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Экспортирование курсов в файлы YML.';

    /**
     * Выполнение команды.
     *
     * @return void
     * @throws DOMException
     */
    public function handle(): void
    {
        $this->line('Начинаем генерацию файла...');

        $export = new Export();
        $bar = $this->output->createProgressBar($export->getTotal());
        $bar->start();

        $export->addEvent('export', function () use ($bar) {
            $bar->advance();
        });

        $export->run();
        $bar->finish();

        if ($export->hasError()) {
            $errors = $export->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка генерации YML файла: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nЭкспорт был завершен.");
        Log::info('Создание YML файла.');
    }
}
