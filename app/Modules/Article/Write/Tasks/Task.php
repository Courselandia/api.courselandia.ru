<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Write\Tasks;

use App\Models\Error;
use App\Models\Event;
use Carbon\Carbon;

/**
 * Абстрактный класс для написания собственных заданий на написания текста.
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
     * Запуск формирования текстов.
     *
     * @param int $index Порядковый номер элемента.
     * @param Carbon|null $delay Дата, на сколько нужно отложить задачу.
     *
     * @return void
     */
    abstract public function run(int $index, Carbon $delay = null): void;
}
