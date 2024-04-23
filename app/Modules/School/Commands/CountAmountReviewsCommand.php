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
use App\Modules\School\Actions\Admin\School\SchoolCountAmountReviewsAction;

/**
 * Подсчет количества отзывов в каждой школе.
 */
class CountAmountReviewsCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'school:count-amount-reviews';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Подсчет количество отзывов в каждой школе.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new SchoolCountAmountReviewsAction();

        $this->line('Запуск подсчета количества отзывах в школах...');

        $bar = $this->output->createProgressBar(SchoolCountAmountReviewsAction::getCountSchools());
        $bar->start();

        $action->addEvent('saved', function () use ($bar) {
            $bar->advance();
        });

        $action->run();

        $this->info("\n\nПодсчет завершен.");
        Log::info('Запуск подсчета количества отзывов для школ.');
    }
}
