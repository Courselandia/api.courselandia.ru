<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Requests\Site;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

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
        'start' => 'string',
        'limit' => 'string',
        'school_id' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('reviews');
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'school_id' => 'required|exists_soft:schools,id',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'start' => 'string',
        'limit' => 'string',
        'school_id' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('review::http.requests.site.reviewReadRequest.sorts'),
            'start' => trans('review::http.requests.site.reviewReadRequest.start'),
            'limit' => trans('review::http.requests.site.reviewReadRequest.limit'),
            'school_id' => trans('review::http.requests.site.reviewReadRequest.schoolId'),
        ];
    }
}
