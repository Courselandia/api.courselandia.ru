<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для удаления FAQ.
 */
class FaqDestroyRequest extends FormRequest
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
            'ids' => trans('faq::http.requests.admin.faqDestroyRequest.ids')
        ];
    }
}
