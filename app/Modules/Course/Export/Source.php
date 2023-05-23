<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Export;

use App\Models\Error;
use App\Models\Event;

/**
 * Абстрактный класс для создания источника формирования JSON файла.
 */
abstract class Source
{
    use Event;

    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    abstract public function count(): int;

    /**
     * Запуск экспорта данных.
     *
     * @return void.
     */
    abstract public function export(): void;
}
