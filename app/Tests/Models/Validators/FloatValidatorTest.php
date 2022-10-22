<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Validators;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\Validators\FloatValidator;
use Tests\CreatesApplication;

/**
 * Тестирование: Классы для валидации дробных чисел.
 */
class FloatValidatorTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     */
    public function testRun(): void
    {
        $validator = new FloatValidator();
        $result = $validator->validate(null, 500.2);

        $this->assertTrue($result);
    }
}
