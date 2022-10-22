<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

/**
 * Класс для валидации массива параметра фильтрации.
 */
class FiltersValidator
{
    /**
     * Валидация.
     *
     * @param  string|null  $attribute  Название атрибута.
     * @param  mixed  $values  Значение для валидации.
     * @param  array  $params  Настройки.
     *
     * @return bool Вернет результат валидации.
     */
    public function validate(?string $attribute, mixed $values, array $params): bool
    {
        if (!is_array($values)) {
            return false;
        }

        foreach ($values as $filed => $value) {
            if (!is_string($filed)) {
                return false;
            }
        }

        if (count($params)) {
            $allowed = true;

            foreach ($values as $filed => $value) {
                if (!in_array($filed, $params)) {
                    $allowed = false;
                }
            }

            return $allowed;
        }

        return true;
    }
}
