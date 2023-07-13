<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use App\Models\Error;
use App\Models\Event;
use EMT\EMTypograph;

/**
 * Абстрактный класс для написания собственных заданий на типографирования текстов.
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
     * Запуск типографирования текстов.
     *
     * @return void
     */
    abstract public function run(): void;
}
