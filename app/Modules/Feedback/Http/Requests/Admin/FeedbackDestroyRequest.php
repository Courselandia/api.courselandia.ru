<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для удаления обратной связи.
 */
class FeedbackDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
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
            'ids' => trans('feedback::http.requests.admin.feedbackDestroyRequest.ids')
        ];
    }
}
