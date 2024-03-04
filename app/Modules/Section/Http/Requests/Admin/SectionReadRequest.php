<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения разделов.
 */
class SectionReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('sections');
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
            'sorts' => trans('section::http.requests.admin.sectionReadRequest.sorts'),
            'offset' => trans('section::http.requests.admin.sectionReadRequest.offset'),
            'limit' => trans('section::http.requests.admin.sectionReadRequest.limit'),
            'filters' => trans('section::http.requests.admin.sectionReadRequest.filters'),
            'filters.status' => trans('section::http.requests.admin.sectionReadRequest.status'),
        ];
    }
}
