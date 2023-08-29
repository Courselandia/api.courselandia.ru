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
use App\Modules\School\Actions\Admin\School\SchoolCountRatingAction;

/**
 * Подсчет рейтинга для каждой школы.
 */
class CountRatingCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'school:count-rating';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Подсчет количество рейтинга для каждой школы.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = app(SchoolCountRatingAction::class);

        $this->line('Запуск подсчета рейтинга для школ...');

        $bar = $this->output->createProgressBar($action->getCountSchools());
        $bar->start();

        $action->addEvent('saved', function () use ($bar) {
            $bar->advance();
        });

        $action->run();

        $this->info("\n\nПодсчет завершен.");
        Log::info('Запуск подсчета рейтинга для каждой школы.');
    }
}
