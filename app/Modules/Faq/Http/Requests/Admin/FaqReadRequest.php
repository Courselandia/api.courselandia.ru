<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения FAQ.
 */
class FaqReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('faqs');

        $columnsFilter = array_merge(
            $columns,
            [
                'school-id'
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
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:published_at',
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
            'sorts' => trans('faq::http.requests.admin.faqReadRequest.sorts'),
            'offset' => trans('faq::http.requests.admin.faqReadRequest.offset'),
            'limit' => trans('faq::http.requests.admin.faqReadRequest.limit'),
            'filters' => trans('faq::http.requests.admin.faqReadRequest.filters'),
            'filters.status' => trans('faq::http.requests.admin.faqReadRequest.status'),
        ];
    }
}
