<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use Carbon\Carbon;
use Validator;

/**
 * Класс для валидации фильтра.
 */
class FilterDateValidator
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

        $data = [];
        $rules = [];

        if (count($params)) {
            foreach ($params as $filed) {
                if (isset($values[$filed])) {
                    if (is_string($values[$filed])) {
                        $nameField = $filed;
                        $data[$filed] = $values[$filed];
                        $rules[$nameField] = 'date_format:Y-m-d O';
                    } else {
                        return false;
                    }
                }
            }
        }

        $validator = Validator::make($data, $rules);

        return $validator->passes();
    }
}
