<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Spatie\LaravelData\Data;

/**
 * Сущность.
 */
abstract class EntityNew extends Data
{
    /**
     * Переводит массив значений в массив сущностей.
     *
     * @param array $values Массив значений.
     * @return array Массив сущностей.
     */
    public static function array(array $values): array
    {
        $result = [];

        foreach ($values as $item) {
            $result[] = self::from($item);
        }

        return $result;
    }
}
