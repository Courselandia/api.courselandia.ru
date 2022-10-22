<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Класс для валидации промежутка дробного числа.
 */
class FloatBetweenValidator
{
    /**
     * Валидация.
     *
     * @param  string|null  $attribute  Название атрибута.
     * @param  mixed  $value  Значение для валидации.
     * @param  array  $parameters  Параметры.
     *
     * @return bool Вернет результат валидации.
     */
    public function validate(?string $attribute, mixed $value, array $parameters): bool
    {
        if (is_numeric($value)) {
            $value = floatval($value);
            $length = strlen((string)$value);

            return $length >= $parameters[0] && $length <= $parameters[1];
        }

        return false;
    }
}
