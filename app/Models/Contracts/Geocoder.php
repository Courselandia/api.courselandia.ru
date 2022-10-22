<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

use App\Models\Geocoder\Coordinate;

/**
 * Абстрактный класс для проектирования собственной системы геокодирования.
 */
abstract class Geocoder
{
    /**
     * Абстрактный метод для получения координат на основе адреса.
     *
     * @param  string|null  $zipCode  Индекс.
     * @param  string|null  $country  Страна.
     * @param  string|null  $city  Город.
     * @param  string|null  $region  Регион.
     * @param  string|null  $street  Улица.
     *
     * @return Coordinate|null Вернет координату местоположения.
     */
    abstract public function get(
        string $zipCode = null,
        string $country = null,
        string $city = null,
        string $region = null,
        string $street = null
    ): ?Coordinate;

    /**
     * Получение адреса.
     *
     * @param  string|null  $zipCode  Индекс.
     * @param  string|null  $country  Страна.
     * @param  string|null  $city  Город.
     * @param  string|null  $region  Регион.
     * @param  string|null  $street  Улица.
     *
     * @return string|null Вернет строку адреса.
     */
    protected function getAddress(
        string $zipCode = null,
        string $country = null,
        string $city = null,
        string $region = null,
        string $street = null
    ): ?string {
        $address = '';

        if ($zipCode) {
            $address .= $zipCode;
        }

        if ($country) {
            if ($address) {
                $address .= ', ';
            }

            $address .= $country;
        }

        if ($region) {
            if ($address) {
                $address .= ', ';
            }

            $address .= $region;
        }

        if ($city) {
            if ($address) {
                $address .= ', ';
            }

            $address .= $city;
        }

        if ($street) {
            if ($address) {
                $address .= ', ';
            }

            $address .= $street;
        }

        return $address;
    }
}
