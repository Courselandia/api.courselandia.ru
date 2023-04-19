<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Review\Commands;

use App\Modules\School\Enums\School;
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
     * Количество импортированных отзывов.
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
        $this->line('Начинаем импорт отзывов...');

        $import = new Import();

        $import->addEvent('imported', function (Import $imp, ParserReview $review, School $school, string $source) {
            $this->amount++;
            $this->line('Создан отзыв для школы ' . $school->getLabel() . ' из ' . $source . ': #' . $review->id);
        });

        $import->addEvent('skipped', function (Import $imp, ParserReview $review, School $school, string $source) {
            $this->warn('Пропущен отзыв для школы ' . $school->getLabel() . ' из ' . $source . ': #' . $review->id);
        });

        $import->run();

        if ($import->hasError()) {
            $errors = $import->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка импорта отзывов: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nИмпортировано отзывов: " . $this->amount . ".");
        Log::info('Импортирование отзывов: ' . $this->amount . ' шт.');
    }
}
