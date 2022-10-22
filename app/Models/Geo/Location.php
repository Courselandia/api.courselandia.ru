<?php
/**
 * Геопозиционирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geo
 */

namespace App\Models\Geo;

/**
 * Класс местонахождения.
 */
class Location
{
    /**
     * Страна.
     *
     * @var string
     */
    private string $country;

    /**
     * Код страны.
     *
     * @var string
     */
    private string $countryCode;

    /**
     * Регион.
     *
     * @var string
     */
    private string $region;

    /**
     * Код региона.
     *
     * @var string
     */
    private string $regionCode;

    /**
     * Город.
     *
     * @var string
     */
    private string $city;

    /**
     * Индекс.
     *
     * @var string
     */
    private string $zip;

    /**
     * Хранит широту.
     *
     * @var float
     */
    private float $latitude;

    /**
     * Хранит долготу.
     *
     * @var float
     */
    private float $longitude;

    /**
     * Получение страны.
     *
     * @return string|null Страна.
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Установка страны.
     *
     * @param  string  $country  Страны.
     *
     * @return $this
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Получение кода страны.
     *
     * @return string|null Код страны.
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Установка кода страны.
     *
     * @param  string  $countryCode  Кода страны.
     *
     * @return $this
     */
    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Получение региона.
     *
     * @return string|null Страна.
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Установка региона.
     *
     * @param  string  $region  Регион.
     *
     * @return $this
     */
    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Получение кода региона.
     *
     * @return string|null Код региона.
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * Установка кода региона.
     *
     * @param  string  $regionCode  Кода региона.
     *
     * @return $this
     */
    public function setRegionCode(string $regionCode): static
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * Получение города.
     *
     * @return string|null Город.
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Установка города.
     *
     * @param  string  $city  Город.
     *
     * @return $this
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Получение индекса.
     *
     * @return string|null Индекс.
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * Установка индекса.
     *
     * @param  string  $zip  Индекс.
     *
     * @return $this
     */
    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Получение широты.
     *
     * @return float|null Широта.
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Установка широты.
     *
     * @param  float  $latitude  Широта.
     *
     * @return $this
     */
    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Получение долготоы.
     *
     * @return float|null Долгота.
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Установка долготы.
     *
     * @param  float  $longitude  Долгота.
     *
     * @return $this
     */
    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
