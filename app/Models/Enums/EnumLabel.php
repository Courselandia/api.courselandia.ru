<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

/**
 * Интерфейс дял получения названия перечисления.
 */
interface EnumLabel
{
    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int;
}