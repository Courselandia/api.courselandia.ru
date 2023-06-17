<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Analyze\Tasks;

use App\Models\Error;
use App\Models\Event;
use Carbon\Carbon;

/**
 * Абстрактный класс для написания собственных заданий на анализ текста.
 */
abstract class Task
{
    use Error;
    use Event;

    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    abstract public function count(): int;

    /**
     * Запуск анализа текстов.
     *
     * @param Carbon|null $delay Дата, на сколько нужно отложить задачу.
     *
     * @return void
     */
    abstract public function run(Carbon $delay = null): void;
}
