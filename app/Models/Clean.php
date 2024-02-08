<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Очистка сущностей от лишних данных.
 */
class Clean
{
    /**
     * Чистка данных.
     *
     * @param array $items Данные для очистки.
     * @param array $removes Массив ключей, которые подлежат очистки.
     * @param bool $ifNull Только удалять если равен null.
     *
     * @return array Вернет очищенные данные.
     */
    public static function do(array $items, array $removes, bool $ifNull = false): array
    {
        foreach ($items as $key => $item) {
            if (is_array($items[$key]) && array_is_list($items[$key])) {
                for ($i = 0; $i < count($items[$key]); $i++) {
                    if (is_array($items[$key][$i])) {
                        $items[$key][$i] = self::do($items[$key][$i], $removes, $ifNull);
                    }
                }
            } elseif (is_array($items[$key])) {
                $items[$key] = self::do($items[$key], $removes, $ifNull);
            } else if (!is_array($items[$key]) && in_array($key, $removes)) {
                if ($ifNull === false) {
                    unset($items[$key]);
                } else if ($ifNull === true && $items[$key] === null) {
                    unset($items[$key]);
                }
            }
        }

        return $items;
    }
}
