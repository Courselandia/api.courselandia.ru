<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Models;

use App\Models\Enums\CacheTime;
use App\Modules\Location\Contracts\Location as Contract;
use App\Modules\Location\Values\City;
use App\Modules\Location\Values\Country;
use App\Modules\Location\Values\Region;
use Cache;
use Geographer;
use Util;

/**
 * Класс для работы с географическими объектами.
 */
class Location extends Contract
{
    /**
     * Получить все страны.
     *
     * @return Country[] Вернет все страны.
     */
    public function getCountries(): array
    {
        $key = Util::getKey('location', 'countries');

        return Cache::tags(['location'])->remember($key, CacheTime::MONTH->value, function () {
            $countries = Geographer::getCountries()->toArray();
            $result = [];

            for ($i = 0; $i < count($countries); $i++) {
                $country = new Country($countries[$i]['code'], $countries[$i]['name']);
                $result[] = $country;
            }

            return $result;
        });
    }

    /**
     * Вернуть название страны по коду.
     *
     * @param string $code Код страны.
     *
     * @return string|null Название страны.
     */
    public function getNameCountry(string $code): ?string
    {
        $countries = $this->getCountries();

        for ($i = 0; $i < count($countries); $i++) {
            if ($countries[$i]->getCode() === $code) {
                return $countries[$i]->getName();
            }
        }

        return null;
    }

    /**
     * Получить все регионы страны.
     *
     * @param string $country Код страны.
     *
     * @return Region[] Регионы страны.
     */
    public function getRegions(string $country): array
    {
        $key = Util::getKey('location', 'regions', $country);

        return Cache::tags(['location'])->remember($key, CacheTime::MONTH->value, function () use ($country) {
            $stages = Geographer::getCountries()->findOne(['code' => $country])->getStates()->toArray();
            $regions = [];

            for ($i = 0; $i < count($stages); $i++) {
                $region = new Region($stages[$i]['isoCode'], $stages[$i]['name']);
                $regions[] = $region;
            }

            return $regions;
        });
    }

    /**
     * Получить название региона по коду региона.
     *
     * @param string $country Код страны.
     * @param string $code Код региона.
     *
     * @return string|null Регион страны.
     */
    public function getNameRegion(string $country, string $code): ?string
    {
        $regions = Location::getRegions($country);

        for ($i = 0; $i < count($regions); $i++) {
            if ($regions[$i]->getCode() === $code) {
                return $regions[$i]->getName();
            }
        }

        return null;
    }

    /**
     * Получить все города по стране и региону.
     *
     * @param string $country Код страны.
     * @param string $region Код региона.
     *
     * @return City[] Города страны и его региона.
     */
    public function getCities(string $country, string $region): array
    {
        $key = Util::getKey('location', 'cities', $country, $region);

        return Cache::tags(['location'])->remember($key, CacheTime::MONTH->value, function () use ($country, $region) {
            $cities = Geographer::getCountries()
                ->findOne(['code' => $country])
                ->getStates()
                ->findOne(['isoCode' => $region])
                ->getCities()
                ->toArray();

            $data = [];

            for ($i = 0; $i < count($cities); $i++) {
                $city = new City($cities[$i]['code'], $cities[$i]['name']);
                $data[] = $city;
            }

            return $data;
        });
    }

    /**
     * Получить название города по его коду.
     *
     * @param string $country Код страны.
     * @param string $region Код региона.
     * @param string $code Код города.
     *
     * @return string|null Название города.
     */
    public function getNameCity(string $country, string $region, string $code): ?string
    {
        $cities = Location::getCities($country, $region);

        for ($i = 0; $i < count($cities); $i++) {
            if ($cities[$i]->getCode() === $code) {
                return $cities[$i]->getName();
            }
        }

        return null;
    }
}
