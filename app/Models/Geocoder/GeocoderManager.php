<?php
/**
 * Геокодирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geocoder
 */

namespace App\Models\Geocoder;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы геокодирования.
 */
class GeocoderManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('geocoder.driver');
    }
}
