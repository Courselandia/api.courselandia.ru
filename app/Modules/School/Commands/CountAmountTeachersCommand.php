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
use App\Modules\School\Actions\Admin\School\SchoolCountAmountTeachersAction;

/**
 * Подсчет количества учителей в каждой школе.
 */
class CountAmountTeachersCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'school:count-amount-teachers';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Подсчет количество учителей в каждой школе.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new SchoolCountAmountTeachersAction();

        $this->line('Запуск подсчета количества учителей в школах...');

        $bar = $this->output->createProgressBar(SchoolCountAmountTeachersAction::getCountSchools());
        $bar->start();

        $action->addEvent('saved', function () use ($bar) {
            $bar->advance();
        });

        $action->run();

        $this->info("\n\nПодсчет завершен.");
        Log::info('Запуск подсчета количества учителей для школ.');
    }
}
