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
 * Класс запрос для создания FAQ.
 */
class FaqCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'school_id' => 'exists_soft:schools,id',
            'status' => 'boolean',
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
            'school_id' => trans('faq::http.requests.admin.faqCreateRequest.schoolId'),
            'status' => trans('faq::http.requests.admin.faqCreateRequest.status'),
        ];
    }
}
