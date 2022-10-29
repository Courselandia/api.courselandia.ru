<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Requests\Admin\Teacher;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания учителя.
 */
class TeacherCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'image' => 'string',
        'directions' => 'string',
        'directions.*' => 'string',
        'schools' => 'string',
        'schools.*' => 'string'
    ])] public function rules(): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp,svg',
            'directions' => 'array',
            'directions.*' => 'integer',
            'schools' => 'array',
            'schools.*' => 'integer',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
        'directions' => 'string',
        'directions.*' => 'string',
        'schools' => 'string',
        'schools.*' => 'string'
    ])] public function attributes(): array
    {
        return [
            'image' => trans('teacher::http.requests.admin.teacherCreateRequest.image'),
            'directions' => trans('teacher::http.requests.admin.teacherCreateRequest.directions'),
            'directions.*' => trans('teacher::http.requests.admin.teacherCreateRequest.directions'),
            'schools' => trans('teacher::http.requests.admin.teacherCreateRequest.schools'),
            'schools.*' => trans('teacher::http.requests.admin.teacherCreateRequest.schools'),
        ];
    }
}
