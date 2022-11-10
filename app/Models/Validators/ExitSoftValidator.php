<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use DB;

/**
 * Классы для валидации проверки существования записи.
 */
class ExitSoftValidator
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
        $value = strtolower(trim($value));
        $query = DB::table($parameters[0])
            ->select($parameters[1])
            ->where($parameters[1], $value)
            ->whereNull('deleted_at')
            ->first();

        return !!$query;
    }
}
