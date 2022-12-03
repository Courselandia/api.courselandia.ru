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
    protected $signature = 'course:import';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Импортирование курсов.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Importing courses from the sources...');

        $import = new Import();

        $import->addEvent('read', function (Import $imp, ParserCourse $course) {
            $this->line('Импортирован курс: ' . $course->school->getLabel() . ' | ' . $course->name);
        });

        $import->run();

        if ($import->hasError()) {
            $errors = $import->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка импорта курса: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        } else {
            $this->info("\n\nИмпортирование курса завершено.");
        }
    }
}
