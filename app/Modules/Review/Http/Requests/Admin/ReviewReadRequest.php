<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для чтения отзывов.
 */
class ReviewReadRequest extends FormRequest
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
        'filters.rating' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('reviews');
        $column[] = 'school-name';
        $column[] = 'course-header';
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $column . '|filter_date_range:published_at',
            'filters.rating' => 'integer',
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
        'filters.rating' => 'string',
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('review::http.requests.admin.reviewReadRequest.sorts'),
            'offset' => trans('review::http.requests.admin.reviewReadRequest.offset'),
            'limit' => trans('review::http.requests.admin.reviewReadRequest.limit'),
            'filters' => trans('review::http.requests.admin.reviewReadRequest.filters'),
            'filters.rating' => trans('review::http.requests.admin.reviewReadRequest.rating'),
            'filters.status' => trans('review::http.requests.admin.reviewReadRequest.status'),
        ];
    }
}
