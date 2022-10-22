<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use MongoDB\BSON\UTCDateTime;

/**
 * Классы для валидации даты в базе данных MongoDB.
 */
class DateMongoValidator
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
        return $value instanceof UTCDateTime;
    }
}
