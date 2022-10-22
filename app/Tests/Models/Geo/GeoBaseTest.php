<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Geo;

use App\Models\Exceptions\CurlException;
use Illuminate\Foundation\Testing\TestCase;
use App\Models\Geo\IpApi;
use Tests\CreatesApplication;

/**
 * Тестирование: Класс драйвер геопозиционирования на основе сервиса ipgeobase.ru.
 */
class GeoBaseTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     * @throws CurlException
     */
    public function testRun(): void
    {
        $geo = new IpApi();
        $location = $geo->get('109.252.118.60');

        $this->assertIsString($location->getCountry());
        $this->assertIsString($location->getCountryCode());
        $this->assertIsString($location->getRegion());
        $this->assertIsString($location->getRegionCode());
        $this->assertIsString($location->getCity());
        $this->assertIsString($location->getZip());
        $this->assertIsFloat($location->getLatitude());
        $this->assertIsFloat($location->getLongitude());
    }
}
