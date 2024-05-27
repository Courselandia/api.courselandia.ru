<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения промокодов.
 */
class PromocodeReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('promocodes');

        $columnsFilter = array_merge(
            $columns,
            [
                'school-id',
                'date',
                'filter_applicable',
            ]
        );

        $columnsSort = array_merge(
            $columns,
            [
                'school-name'
            ]
        );

        return [
            'sorts' => 'array|sorts:' . implode(',', $columnsSort),
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:date_start|filter_date_range:date_end',
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
            'sorts' => trans('promocode::http.requests.admin.promocodeReadRequest.sorts'),
            'offset' => trans('promocode::http.requests.admin.promocodeReadRequest.offset'),
            'limit' => trans('promocode::http.requests.admin.promocodeReadRequest.limit'),
            'filters' => trans('promocode::http.requests.admin.promocodeReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
