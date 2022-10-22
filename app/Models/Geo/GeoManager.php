<?php
/**
 * Геопозиционирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geo
 */

namespace App\Models\Geo;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы геопозиционирования.
 */
class GeoManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('geo.driver');
    }
}
