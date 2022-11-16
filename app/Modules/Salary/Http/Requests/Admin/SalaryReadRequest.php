<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Salary\Enums\Level;
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
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.level' => 'string',
        'filters.level.*' => 'string',
        'filters.salary' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('salaries');
        $column[] = 'profession-name';
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $column . '|filter_date_range:published_at',
            'filters.level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'filters.level.*' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'filters.salary' => 'integer',
            'filters.status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.level' => 'string',
        'filters.level.*' => 'string',
        'filters.salary' => 'string',
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('salary::http.requests.admin.salaryReadRequest.sorts'),
            'offset' => trans('salary::http.requests.admin.salaryReadRequest.offset'),
            'limit' => trans('salary::http.requests.admin.salaryReadRequest.limit'),
            'filters' => trans('salary::http.requests.admin.salaryReadRequest.filters'),
            'filters.level' => trans('salary::http.requests.admin.salaryReadRequest.level'),
            'filters.level.*' => trans('salary::http.requests.admin.salaryReadRequest.level'),
            'filters.salary' => trans('salary::http.requests.admin.salaryReadRequest.salary'),
            'filters.status' => trans('salary::http.requests.admin.salaryReadRequest.status'),
        ];
    }
}
