<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения инструментов.
 */
class ToolReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('tools');
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
            'sorts' => trans('tool::http.requests.admin.toolReadRequest.sorts'),
            'offset' => trans('tool::http.requests.admin.toolReadRequest.offset'),
            'limit' => trans('tool::http.requests.admin.toolReadRequest.limit'),
            'filters' => trans('tool::http.requests.admin.toolReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
