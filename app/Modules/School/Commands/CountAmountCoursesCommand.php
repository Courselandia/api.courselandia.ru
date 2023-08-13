<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Commands;

use Log;
use Illuminate\Console\Command;
use App\Modules\School\Actions\Admin\School\SchoolCountAmountCoursesAction;

/**
 * Подсчет количества курсов в каждой школе.
 */
class CountAmountCoursesCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'school:count-amount-courses';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Подсчет количество курсов в каждой школе.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = app(SchoolCountAmountCoursesAction::class);

        $this->line('Запуск подсчета количеста курсов в школах...');

        $bar = $this->output->createProgressBar(SchoolCountAmountCoursesAction::getCountSchools());
        $bar->start();

        $action->addEvent('saved', function () use ($bar) {
            $bar->advance();
        });

        $action->run();

        $this->info("\n\nПодсчет завершен.");
        Log::info('Запуск подсчета количеста курсов для школ.');
    }
}
