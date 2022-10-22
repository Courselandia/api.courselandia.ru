<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Классы для валидации телефона.
 */
class PhoneValidator
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
        return preg_match('/\+'.$parameters[0].'( )?(\(|-)\d{3,3}\)?(-| )\d{3,3}-\d{4,4}/', $value);
    }
}
