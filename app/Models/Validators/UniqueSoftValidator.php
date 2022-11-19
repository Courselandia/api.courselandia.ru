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
 * Классы для валидации уникальных записей для мягкого удаления.
 */
class UniqueSoftValidator
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
        $queries = DB::table($parameters[0])
            ->selectRaw($parameters[0].".*, LOWER(TRIM(`$parameters[1]`)) as `$parameters[1]`");

        for ($i = 3; $i < count($parameters); $i = $i + 2) {
            if (isset($parameters[$i + 1])) {
                $queries->where($parameters[$i], $parameters[$i + 1]);
            }
        }

        $queries->whereNull('deleted_at');
        $queries = $queries->get();
        $nameParam = @$parameters[3];

        foreach ($queries as $query) {
            if ($value == strtolower($query->{$parameters[1]}) && !$nameParam) {
                return false;
            } elseif ($value == strtolower($query->{$parameters[1]}) && $query->{$nameParam} != $parameters[2]) {
                return false;
            }
        }

        return true;
    }
}
