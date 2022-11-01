<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для чтения зарплат.
 */
class SalaryReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'start' => 'string',
        'limit' => 'string',
        'filters' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('salaries');
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:'.$column,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:'.$column.'|filter_date_range:published_at',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'start' => 'string',
        'limit' => 'string',
        'filters' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('salary::http.requests.admin.salaryReadRequest.sorts'),
            'start' => trans('salary::http.requests.admin.salaryReadRequest.start'),
            'limit' => trans('salary::http.requests.admin.salaryReadRequest.limit'),
            'filters' => trans('salary::http.requests.admin.salaryReadRequest.filters'),
        ];
    }
}
