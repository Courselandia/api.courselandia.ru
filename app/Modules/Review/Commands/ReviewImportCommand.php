<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Review\Commands;

use Log;
use App\Modules\Review\Entities\ParserReview;
use App\Modules\Review\Imports\Import;
use Illuminate\Console\Command;

/**
 * Импорт отзывов.
 */
class ReviewImportCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'review:import';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Импортирование отзывов с разных источников.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Начинаем импорт отзывов...');

        $import = new Import();

        $import->addEvent('imported', function (ParserReview $review) {
            $this->info('Создан отзыв: #' . $review->id);
        });

        $import->run();

        $this->info("\n\nИмпорт завершен.");
        Log::info('Импортирование отзывов.');
    }
}
