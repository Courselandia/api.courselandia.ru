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
 * Класс запрос для обновления статуса категории.
 */
class FaqUpdateStatusRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['status' => 'string'])] public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['status' => 'string'])] public function attributes(): array
    {
        return [
            'status' => trans('category::http.requests.admin.categoryUpdateStatusRequest.status'),
        ];
    }
}
