<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для обновления статуса категории.
 */
class CategoryUpdateStatusRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
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
    public function attributes(): array
    {
        return [
            'status' => trans('category::http.requests.admin.categoryUpdateStatusRequest.status'),
        ];
    }
}
