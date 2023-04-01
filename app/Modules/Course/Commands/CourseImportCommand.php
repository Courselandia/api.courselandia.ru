<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use Log;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Imports\Import;
use Illuminate\Console\Command;

/**
 * Импорт курсов.
 */
class CourseImportCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:import
        {--reload-images : Перезагрузить все изображения}
    ';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Импортирование курсов.';

    /**
     * Количество импортированных курсов.
     *
     * @var int
     */
    private int $amount = 0;

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Импортирование курсов с источников...');

        $import = new Import();
        $import->setReloadImages($this->option('reload-images'));
        $this->amount = 0;

        $import->addEvent('read', function (Import $imp, ParserCourse $course) {
            $this->line('Импортирован курс: ' . $course->school->getLabel() . ' | ' . $course->name);
            $this->amount++;
        });

        $import->run();

        if ($import->hasError()) {
            $errors = $import->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка импорта курса: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nИмпортирование курсов завершено: " . $this->amount . " шт.");
        Log::info('Импортирование курсов.');
    }
}
