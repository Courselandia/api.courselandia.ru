<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания категории.
 */
class CategoryCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'directions' => 'string',
        'directions.*' => 'string',
        'professions' => 'string',
        'professions.*' => 'string',
        'status' => 'boolean',
    ])] public function rules(): array
    {
        return [
            'directions' => 'array',
            'directions.*' => 'exists_soft:directions,id',
            'professions' => 'array',
            'professions.*' => 'exists_soft:professions,id',
            'status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'directions' => 'string',
        'directions.*' => 'string',
        'professions' => 'string',
        'professions.*' => 'string',
        'boolean' => 'string'
    ])] public function attributes(): array
    {
        return [
            'directions' => trans('category::http.requests.admin.categoryCreateRequest.directions'),
            'directions.*' => trans('category::http.requests.admin.categoryCreateRequest.directions'),
            'professions' => trans('category::http.requests.admin.categoryCreateRequest.professions'),
            'professions.*' => trans('category::http.requests.admin.categoryCreateRequest.professions'),
            'boolean' => trans('category::http.requests.admin.categoryCreateRequest.boolean'),
        ];
    }
}
