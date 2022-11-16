<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Http\Requests\Admin;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения записи обратной связи.
 */
class FeedbackReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['sorts' => 'string', 'offset' => 'string', 'limit' => 'string', 'filters' => 'string'])] public function rules(): array
    {
        $columnSorts = Schema::getColumnListing('feedbacks');
        $columnSorts = implode(',', $columnSorts);

        $columnFilters = [
            'id',
            'name',
            'email',
            'phone',
            'message',
            'created_at',
        ];
        $columnFilters = implode(',', $columnFilters);

        return [
            'sorts' => 'array|sorts:'.$columnSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:'.$columnFilters.'|filter_date_range:created_at',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['sorts' => 'string', 'offset' => 'string', 'limit' => 'string', 'filters' => 'string'])] public function attributes(): array
    {
        return [
            'sorts' => trans('feedback::http.requests.admin.feedbackReadRequest.sorts'),
            'offset' => trans('feedback::http.requests.admin.feedbackReadRequest.offset'),
            'limit' => trans('feedback::http.requests.admin.feedbackReadRequest.limit'),
            'filters' => trans('feedback::http.requests.admin.feedbackReadRequest.filters')
        ];
    }
}
