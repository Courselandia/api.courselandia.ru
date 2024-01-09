<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\School;

use Schema;
use App\Models\FormRequest;

/**
 * Класс запрос для чтения школ.
 */
class SchoolReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columnsSorts = Schema::getColumnListing('schools');
        $columnsSorts = implode(',', $columnsSorts);

        $columnsFilters = [
            'id',
            'name',
            'status',
        ];
        $columnsFilters = implode(',', $columnsFilters);

        return [
            'sorts' => 'array|sorts:' . $columnsSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnsFilters,
            'filters.status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    public function attributes(): array
    {
        return [
            'sorts' => trans('school::http.requests.admin.schoolReadRequest.sorts'),
            'offset' => trans('school::http.requests.admin.schoolReadRequest.offset'),
            'limit' => trans('school::http.requests.admin.schoolReadRequest.limit'),
            'filters' => trans('school::http.requests.admin.schoolReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
