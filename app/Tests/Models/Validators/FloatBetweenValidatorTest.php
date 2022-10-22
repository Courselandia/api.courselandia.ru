<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Tests.Models
 */

namespace App\Tests\Models\Validators;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\Validators\FloatBetweenValidator;
use Tests\CreatesApplication;

/**
 * Тестирование: Класс для валидации промежутка дробного числа.
 */
class FloatBetweenValidatorTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     */
    public function testRun(): void
    {
        $validator = new FloatBetweenValidator();
        $result = $validator->validate(null, 500.2, [2, 6]);

        $this->assertTrue($result);
    }
}
