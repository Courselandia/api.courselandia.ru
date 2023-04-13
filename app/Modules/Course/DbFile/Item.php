<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile;

use App\Modules\Course\Entities\CourseRead;

/**
 * Класс структура для хранения данных, которые пойдут в файл.
 */
class Item
{
    /**
     * ID данных.
     *
     * @var int|string|null
     */
    public int|string|null $id;

    /**
     * Данные для сохранения.
     *
     * @var CourseRead
     */
    public CourseRead $data;
}
