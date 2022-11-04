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
    #[ArrayShape([
        'school_id' => 'string',
        'status' => 'string',
    ])] public function rules(): array
    {
        return [
            'school_id' => 'exists:schools,id',
            'status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'school_id' => 'string',
        'status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'school_id' => trans('faq::http.requests.admin.faqCreateRequest.schoolId'),
            'status' => trans('faq::http.requests.admin.faqCreateRequest.status'),
        ];
    }
}
