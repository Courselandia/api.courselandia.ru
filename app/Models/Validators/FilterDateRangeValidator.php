<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use Carbon\Carbon;
use Exception;
use Validator;

/**
 * Класс для валидации фильтра, который содержит дату от и до.
 */
class FilterDateRangeValidator
{
    /**
     * Валидация.
     *
     * @param string|null $attribute Название атрибута.
     * @param mixed $values Значение для валидации.
     * @param array $params Настройки.
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
                    if (is_array($values[$filed])) {
                        $nameField = $filed . '.*';
                        $data[$filed] = $values[$filed];
                        $rules[$nameField] = 'date_format:Y-m-d O';

                        try {
                            $dateStart = Carbon::createFromFormat('Y-m-d O', $values[$filed][0]);
                            $dateEnd = Carbon::createFromFormat('Y-m-d O', $values[$filed][1]);

                            if ($dateStart > $dateEnd) {
                                return false;
                            }
                        } catch (Exception $error) {
                            return false;
                        }
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
