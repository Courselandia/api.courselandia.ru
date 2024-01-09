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
 * Класс запрос для удаления категории.
 */
class CategoryDestroyRequest extends FormRequest
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
            'ids' => trans('category::http.requests.admin.categoryDestroyRequest.ids')
        ];
    }
}
