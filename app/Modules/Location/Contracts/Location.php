<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы с странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Contracts;

/**
 * Контрактный класс для создания своего класса позволяющего работать с географическими объектами.
 */
abstract class Location
{
    /**
     * Получить все страны.
     *
     * @return array Вернет все страны.
     */
    abstract public function getCountries(): array;

    /**
     * Вернуть название страны по коду.
     *
     * @param string $code Код страны.
     *
     * @return string|null Название страны.
     */
    abstract public function getNameCountry(string $code): ?string;

    /**
     * Получить все регионы страны.
     *
     * @param string $country Код страны.
     *
     * @return array Регионы страны.
     */
    abstract public function getRegions(string $country): array;

    /**
     * Получить название региона по коду региона.
     *
     * @param string $country Код страны.
     * @param string $code Код региона.
     *
     * @return string|null Регионы страны.
     */
    abstract public function getNameRegion(string $country, string $code): ?string;

    /**
     * Получить все города по стране и региону.
     *
     * @param string $country Код страны.
     * @param string $region Код региона.
     *
     * @return array Города страны и его региона.
     */
    abstract public function getCities(string $country, string $region): array;

    /**
     * Получить название города по его коду.
     *
     * @param string $country Код страны.
     * @param string $region Код региона.
     * @param string $code Код города.
     *
     * @return string|null Название города.
     */
    abstract public function getNameCity(string $country, string $region, string $code): ?string;
}
