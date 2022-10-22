<?php
/**
 * Геокодирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geocoder
 */

namespace App\Models\Geocoder;

/**
 * Класс координат.
 */
class Coordinate
{
    /**
     * Широта.
     *
     * @var float
     */
    private float $latitude;

    /**
     * Долгота.
     *
     * @var float
     */
    private float $longitude;

    /**
     * Конструктор.
     *
     * @param  float  $latitude  Широта.
     * @param  float  $longitude  Долгота.
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->set($latitude, $longitude);
    }

    /**
     * Установка координат.
     *
     * @param  float  $latitude  Широта.
     * @param  float  $longitude  Долгота.
     *
     * @return $this
     */
    public function set(float $latitude, float $longitude): static
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Получение широты.
     *
     * @return float Широта.
     */
    public function getLatitude(): float
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
     * @return float Долгота.
     */
    public function getLongitude(): float
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
