<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Классы для валидации пути.
 */
class PathValidator
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
        return preg_match('/^[^*?"<>|:]*$/', $value);
    }
}
