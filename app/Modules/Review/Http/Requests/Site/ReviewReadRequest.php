<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Requests\Site;

use Schema;
use App\Models\FormRequest;

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
    public function rules(): array
    {
        $columns = Schema::getColumnListing('reviews');
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'school_id' => 'exists_soft:schools,id',
            'link' => 'exists_soft:schools,link',
            'rating' => 'integer|digits_between:0,1',
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
            'sorts' => trans('review::http.requests.site.reviewReadRequest.sorts'),
            'offset' => trans('review::http.requests.site.reviewReadRequest.offset'),
            'limit' => trans('review::http.requests.site.reviewReadRequest.limit'),
            'school_id' => trans('review::http.requests.site.reviewReadRequest.schoolId'),
            'link' => trans('review::http.requests.site.reviewReadRequest.link'),
            'rating' => trans('review::http.requests.site.reviewReadRequest.link'),
        ];
    }
}
