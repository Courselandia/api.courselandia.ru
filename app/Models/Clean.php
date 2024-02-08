<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use App\Modules\Course\Data\Decorators\CourseRead;
use Spatie\LaravelData\DataCollection;

/**
 * Очистка сущностей от лишних данных.
 */
class Clean
{
    /**
     * Чистка данных.
     *
     * @param CourseRead|Data|DataCollection|array $items Данные для очистки.
     * @param array $removes Массив ключей, которые подлежат очистки.
     * @param bool $ifNull Только удалять если равен null.
     *
     * @return CourseRead|DataCollection Вернет очищенные данные.
     */
    public static function do(CourseRead|Data|DataCollection|array $items, array $removes, bool $ifNull = false): CourseRead | array
    {
        $isArray = is_array($items);
        $items = $isArray ? $items : [$items];

        foreach ($items as $item) {
            foreach ($item as $key => $value) {
                if (is_array($item->$key) && array_is_list($item->$key)) {
                    for ($i = 0; $i < count($item->$key); $i++) {
                        if ($item->$key[$i] instanceof Data) {
                            $item->$key[$i] = self::do($item->$key[$i], $removes, $ifNull);
                        }
                    }
                } elseif ($item->$key instanceof Data) {
                    $item->$key = self::do($item->$key, $removes, $ifNull);
                } elseif (in_array($key, $removes)) {
                    if ($ifNull === false) {
                        $item->$key = null;
                    } else if ($ifNull === true && $item->$key === null) {
                        $item->$key = null;
                    }
                }
            }
        }

        return $isArray ? $items : $items[0];
    }
}
