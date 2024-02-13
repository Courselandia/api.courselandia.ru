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
    public function rules(): array
    {
        $columnsSorts = Schema::getColumnListing('feedbacks');
        $columnsSorts = implode(',', $columnsSorts);

        $columnsFilters = [
            'id',
            'name',
            'email',
            'phone',
            'message',
            'created_at',
        ];
        $columnsFilters = implode(',', $columnsFilters);

        return [
            'sorts' => 'array|sorts:' . $columnsSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnsFilters . '|filter_date_range:created_at',
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
            'sorts' => trans('feedback::http.requests.admin.feedbackReadRequest.sorts'),
            'offset' => trans('feedback::http.requests.admin.feedbackReadRequest.offset'),
            'limit' => trans('feedback::http.requests.admin.feedbackReadRequest.limit'),
            'filters' => trans('feedback::http.requests.admin.feedbackReadRequest.filters')
        ];
    }
}
