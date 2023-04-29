<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use Log;
use App\Modules\Course\Normalize\Normalize;
use Illuminate\Console\Command;

/**
 * Нормализация каталога курсов.
 */
class CourseNormalizeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:normalize';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Нормализация каталога курсов, для ускорения работы фильтров.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Начинаем нормализацию...');

        $normalize = new Normalize();
        $bar = $this->output->createProgressBar($normalize->getTotal());
        $bar->start();

        $normalize->addEvent('normalized', function () use ($bar) {
            $bar->advance();
        });

        $normalize->run();
        $bar->finish();

        if ($normalize->hasError()) {
            $errors = $normalize->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка нормализации курса: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nНормализация была завершена.");
        Log::info('Нормализация каталога курсов.');
    }
}
