<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Commands;

use Log;
use App\Modules\Promocode\Entities\ParserPromocode;
use App\Modules\Promocode\Imports\Import;
use Illuminate\Console\Command;

/**
 * Импорт промокодов.
 */
class PromocodeImportCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'promocode:import';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Импортирование промокодов.';

    /**
     * Количество импортированных промокодов.
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
        $this->line('Импортирование промокодов с источников...');

        $import = new Import();
        $this->amount = 0;

        $import->addEvent('read', function (Import $imp, ParserPromocode $promocode) {
            $this->line('Импортирован промокод: ' . $promocode->school->getLabel() . ' | ' . $promocode->title);
            $this->amount++;
        });

        $import->run();

        if ($import->hasError()) {
            $errors = $import->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка импорта промокода: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nИмпортирование промокодов завершено: " . $this->amount . " шт.");
        Log::info('Импортирование промокодов.');
    }
}
