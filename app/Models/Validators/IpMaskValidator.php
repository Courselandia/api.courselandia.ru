<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Классы для валидации маски IP.
 */
class IpMaskValidator
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
        return preg_match(
            '/^(([0-9]{1,3})|(\*{1}))\.(([0-9]{1,3})|(\*{1}))\.(([0-9]{1,3})|(\*{1}))\.(([0-9]{1,3})|(\*{1}))$/',
            $value
        );
    }
}
