<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения направлений.
 */
class DirectionReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('directions');
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columns . '|filter_date_range:published_at',
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
            'sorts' => trans('direction::http.requests.admin.directionReadRequest.sorts'),
            'offset' => trans('direction::http.requests.admin.directionReadRequest.offset'),
            'limit' => trans('direction::http.requests.admin.directionReadRequest.limit'),
            'filters' => trans('direction::http.requests.admin.directionReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
