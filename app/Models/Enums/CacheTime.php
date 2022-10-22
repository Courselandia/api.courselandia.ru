<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

/**
 * Время для кеширования.
 */
enum CacheTime: int
{
    /**
     * Общий.
     */
    case GENERAL = 86400;

    /**
     * Месяц.
     */
    case MONTH = 2592000;
}