<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

class EnumList
{
    /**
     * Вернет список из названий и значений Enum.
     *
     * @return array<string, string> Вернет массив из названий и значений.
     */
    public static function getList(mixed $enum): array
    {
        $variants = $enum::cases();
        $list = [];

        foreach ($variants as $variant) {
            $list[$variant->value] = $variant->getLabel();
        }

        return $list;
    }

    /**
     * Вернет список из названий Enum.
     *
     * @return array Вернет массив из названий.
     */
    public static function getValues(mixed $enum): array
    {
        $variants = $enum::cases();
        $list = [];

        foreach ($variants as $variant) {
            $list[] = $variant->value;
        }

        return $list;
    }
}