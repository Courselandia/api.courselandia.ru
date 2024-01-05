<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic;

use App\Models\Error;
use App\Models\Event;

/**
 * Абстрактный класс для создания источника экспортирования в Elasticsearch.
 */
abstract class Source
{
    use Error;
    use Event;

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    abstract public function count(): int;

    /**
     * Запуск процесса экспортирования.
     *
     * @return void
     */
    abstract public function export(): void;
}
