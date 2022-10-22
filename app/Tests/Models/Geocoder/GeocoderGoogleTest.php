<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Geocoder;

use App\Models\Exceptions\CurlException;
use App\Models\Exceptions\ResponseException;
use App\Models\Geocoder\GeocoderGoogle;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Тестирование: Класс драйвер геокодирования на основе сервиса Google.com.
 */
class GeocoderGoogleTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     * @throws CurlException
     * @throws ResponseException
     */
    public function testRun(): void
    {
        $geocoder = new GeocoderGoogle();
        $coordinate = $geocoder->get('680009', 'Россия', 'Хабаровск', null, 'ул. Ким-Ю-Чена, 33');

        $this->assertIsFloat($coordinate->getLatitude());
        $this->assertIsFloat($coordinate->getLongitude());
    }
}
