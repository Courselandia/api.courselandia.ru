<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Commands;

use Log;
use App\Modules\Promotion\Entities\ParserPromotion;
use App\Modules\Promotion\Imports\Import;
use Illuminate\Console\Command;

/**
 * Импорт промоакций.
 */
class PromotionImportCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'promotion:import';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Импортирование промоакций.';

    /**
     * Количество импортированных промоакций.
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
        $this->line('Импортирование промоакций с источников...');

        $import = new Import();
        $this->amount = 0;

        $import->addEvent('read', function (Import $imp, ParserPromotion $promotion) {
            $this->line('Импортирована промоакция: ' . $promotion->school->getLabel() . ' | ' . $promotion->title);
            $this->amount++;
        });

        $import->run();

        if ($import->hasError()) {
            $errors = $import->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка импорта промоакции: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nИмпортирование промоакций завершено: " . $this->amount . " шт.");
        Log::info('Импортирование промоакций.');
    }
}
