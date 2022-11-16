<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
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
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('faqs');
        $column[] = 'school-name';
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $column . '|filter_date_range:published_at',
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
        'filters.status' => 'string',
    ])] public function attributes(): array
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
