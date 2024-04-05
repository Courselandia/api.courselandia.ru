<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Helpers;

use App\Models\Clean;
use App\Modules\Course\Helpers\CleanCourseRead;

/**
 * Очистка для чтения коллекций курсов.
 */
class CleanCourseCollectionRead
{
    public static function do(array $data): array
    {
        return Clean::do($data, CleanCourseRead::REMOVES);
    }
}
