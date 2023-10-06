<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Requests\Admin\Teacher;

use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания учителя.
 */
class TeacherUpdateRequest extends TeacherCreateRequest
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
        'schools.*' => 'string',
        'rating' => 'string',
        'copied' => 'string',
        'status' => 'string',
        'experiences' => 'string',
        'experiences.*' => 'string',
    ])] public function rules(): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp,svg',
            'directions' => 'array',
            'directions.*' => 'integer',
            'schools' => 'array',
            'schools.*' => 'integer',
            'rating' => 'nullable|float',
            'copied' => 'boolean',
            'status' => 'boolean',
            'experiences' => 'array',
            'experiences.*' => 'array:place,position,started,finished,weight',
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
        'schools.*' => 'string',
        'rating' => 'string',
        'copied' => 'string',
        'status' => 'string',
        'experiences' => 'string',
        'experiences.*' => 'string',
    ])] public function attributes(): array
    {
        return [
            'image' => trans('teacher::http.requests.admin.teacherCreateRequest.image'),
            'directions' => trans('teacher::http.requests.admin.teacherCreateRequest.directions'),
            'directions.*' => trans('teacher::http.requests.admin.teacherCreateRequest.directions'),
            'schools' => trans('teacher::http.requests.admin.teacherCreateRequest.schools'),
            'schools.*' => trans('teacher::http.requests.admin.teacherCreateRequest.schools'),
            'rating' => trans('teacher::http.requests.admin.teacherCreateRequest.rating'),
            'copied' => trans('teacher::http.requests.admin.teacherCreateRequest.copied'),
            'status' => trans('teacher::http.requests.admin.teacherCreateRequest.status'),
            'experiences' => trans('teacher::http.requests.admin.teacherCreateRequest.experiences'),
            'experiences.*' => trans('teacher::http.requests.admin.teacherCreateRequest.experiences'),
        ];
    }
}
