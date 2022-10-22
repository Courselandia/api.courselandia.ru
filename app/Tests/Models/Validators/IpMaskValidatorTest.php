<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Validators;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\Validators\IpMaskValidator;
use Tests\CreatesApplication;

/**
 * Тестирование: Классы для валидации дробных чисел.
 */
class IpMaskValidatorTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     */
    public function testRun(): void
    {
        $validator = new IpMaskValidator();
        $result = $validator->validate(null, '128.0.0.*');

        $this->assertTrue($result);
    }
}
