<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use App\Models\Enums\SortDirection;

/**
 * Класс для валидации массива параметра сортировки.
 */
class SortsValidator
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

        foreach ($values as $filed => $order) {
            if (!is_string($filed) || !is_string($order)) {
                return false;
            }

            if (!SortDirection::tryFrom($order)) {
                return false;
            }
        }

        if (count($params)) {
            $allowed = true;

            foreach ($values as $filed => $order) {
                if (!in_array($filed, $params)) {
                    $allowed = false;
                }
            }

            return $allowed;
        }

        return true;
    }
}
