<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения категорий.
 */
class CategoryReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('categories');
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
            'sorts' => trans('category::http.requests.admin.categoryReadRequest.sorts'),
            'offset' => trans('category::http.requests.admin.categoryReadRequest.offset'),
            'limit' => trans('category::http.requests.admin.categoryReadRequest.limit'),
            'filters' => trans('category::http.requests.admin.categoryReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
