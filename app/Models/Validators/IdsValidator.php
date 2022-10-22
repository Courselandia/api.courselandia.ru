<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Класс для валидации массива IDs.
 */
class IdsValidator
{
    /**
     * Валидация.
     *
     * @param  string|null  $attribute  Название атрибута.
     * @param  mixed  $value  Значение для валидации.
     *
     * @return bool Вернет результат валидации.
     */
    public function validate(?string $attribute, mixed $value): bool
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        } else {
            return false;
        }

        if (!is_array($value)) {
            return false;
        }

        return array_is_list($value);
    }
}
