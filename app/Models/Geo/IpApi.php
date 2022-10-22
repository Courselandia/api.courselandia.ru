<?php
/**
 * Геопозиционирование.
 * Пакет содержит классы для получения местоположения пользователя по его IP.
 *
 * @package App.Models.Geo
 */

namespace App\Models\Geo;

use App\Models\Contracts\Geo;
use App\Models\Exceptions\CurlException;
use Request;

/**
 * Класс драйвер геопозиционирования на основе сервиса ip-api.com.
 */
class IpApi extends Geo
{
    /**
     * Метод для получения геообъекта.
     *
     * @param  string|null  $ip  IP пользователя. Если не указать, получить IP текущего пользователя.
     *
     * @return Location|null Вернет местонахождение.
     * @throws CurlException
     */
    public function get(string $ip = null): ?Location
    {
        $ip = $ip ?? Request::ip();

        $ch = curl_init('http://ip-api.com/json/'.$ip);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        $response = curl_exec($ch);

        if ($response) {
            $data = json_decode($response, true);

            if ($data['status'] === 'success') {
                $location = new Location();

                $location->setCountry($data['country'])
                    ->setCountryCode($data['countryCode'])
                    ->setRegion($data['regionName'])
                    ->setRegionCode($data['region'])
                    ->setCity($data['city'])
                    ->setZip($data['zip'])
                    ->setLatitude($data['lat'])
                    ->setLongitude($data['lon']);

                return $location;
            } else {
                throw new CurlException($data['message']);
            }
        } elseif (curl_error($ch)) {
            throw new CurlException(curl_error($ch));
        }

        return null;
    }
}
