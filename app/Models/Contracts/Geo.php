<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

use App\Models\Geo\Location;

/**
 * Абстрактный класс для проектирования собственной системы геопозиционирования.
 */
abstract class Geo
{
    /**
     * Абстрактный метод для получения геообъекта.
     *
     * @param  string|null  $ip  IP пользователя. Если не указать, получить IP текущего пользователя.
     *
     * @return Location|null Вернет местонахождение.
     */
    abstract public function get(string $ip = null): ?Location;
}
