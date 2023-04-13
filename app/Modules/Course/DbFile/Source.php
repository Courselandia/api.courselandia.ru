<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile;

use App\Models\Error;
use Generator;

/**
 * Абстрактный класс для создания источника формирования JSON файла.
 */
abstract class Source
{
    use Error;

    /**
     * Получить путь к папке хранения файлов.
     *
     * @return string Путь к папке.
     */
    abstract public function getPathToDir(): string;

    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    abstract public function count(): int;

    /**
     * Чтение данных.
     *
     * @return Generator<Item> Элемент для сохранения.
     */
    abstract public function read(): Generator;
}
