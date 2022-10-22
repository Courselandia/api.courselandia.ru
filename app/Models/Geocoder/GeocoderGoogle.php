<?php
/**
 * Геокодирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geocoder
 */

namespace App\Models\Geocoder;

use Config;
use App\Models\Contracts\Geocoder;
use App\Models\Exceptions\CurlException;
use App\Models\Exceptions\ResponseException;

/**
 * Класс драйвер геокодирования на основе сервиса Google.com.
 */
class GeocoderGoogle extends Geocoder
{
    /**
     * Метод для получения координат на основе адреса.
     *
     * @param  string|null  $zipCode  Индекс.
     * @param  string|null  $country  Страна.
     * @param  string|null  $city  Город.
     * @param  string|null  $region  Регион.
     * @param  string|null  $street  Улица.
     *
     * @return Coordinate|null Вернет координату местоположения.
     * @throws CurlException
     * @throws ResponseException
     */
    public function get(
        string $zipCode = null,
        string $country = null,
        string $city = null,
        string $region = null,
        string $street = null
    ): ?Coordinate {
        $address = $this->getAddress($zipCode, $country, $city, $region, $street);

        if ($address) {
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.Config::get(
                    'geocoder.channels.google.key'
                );

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);
            $data = json_decode($data, true);

            if ($data) {
                if ($data['status'] === 'OK') {
                    return new Coordinate(
                        $data['results'][0]['geometry']['location']['lat'],
                        $data['results'][0]['geometry']['location']['lng']
                    );
                } else {
                    throw new ResponseException($data['error_message']);
                }
            } elseif (curl_error($ch)) {
                throw new CurlException(curl_error($ch));
            }
        }

        return null;
    }
}
