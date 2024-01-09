<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Http\Requests\Site;

use App\Models\FormRequest;

/**
 * Класс запрос для отправки сообщения через сайт.
 */
class FeedbackSendRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|between:1,191',
            'email' => 'required|email',
            'phone' => 'nullable|phone:7',
            'message' => 'nullable|max:5000',
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
            'name' => trans('feedback::http.requests.site.feedbackSendRequest.name'),
            'email' => trans('feedback::http.requests.site.feedbackSendRequest.email'),
            'phone' => trans('feedback::http.requests.site.feedbackSendRequest.phone'),
            'message' => trans('feedback::http.requests.site.feedbackSendRequest.message')
        ];
    }
}
