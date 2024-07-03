<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize;

use App\Models\Error;
use App\Models\Event;

/**
 * Воркер для нормализации.
 */
abstract class Worker
{
    use Event;
    use Error;

    /**
     * Вернет общее количество обрабатываемых записей.
     *
     * @return int Количество записей.
     */
    abstract public function total(): int;

    /**
     * Процесс нормализации.
     *
     * @return void.
     */
    abstract public function run(): void;
}