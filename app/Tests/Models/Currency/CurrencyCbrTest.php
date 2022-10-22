<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Currency;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase;
use App\Models\Currency\CurrencyCbr;
use Tests\CreatesApplication;

/**
 * Тестирование: Класс драйвер для удаленного получения котировок с центробанка России.
 */
class CurrencyCbrTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной валюты в другую.
     *
     * @return void
     */
    public function testRun(): void
    {
        $currency = new CurrencyCbr();
        $result = $currency->get(Carbon::now(), 'USD');

        $this->assertIsFloat($result);
    }
}
