<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Validators;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\Validators\PhoneValidator;
use Tests\CreatesApplication;

/**
 * Тестирование: Классы для валидации дробных чисел.
 */
class PhoneValidatorTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     */
    public function testRun(): void
    {
        $validator = new PhoneValidator();
        $result = $validator->validate(null, '+7-999-099-9000', ['7']);

        $this->assertTrue($result);
    }
}
