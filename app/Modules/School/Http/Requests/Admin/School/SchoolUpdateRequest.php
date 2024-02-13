<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\School;

/**
 * Класс запрос для создания школ.
 */
class SchoolUpdateRequest extends SchoolCreateRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'imageLogo' => 'nullable|media:jpg,png,gif,webp,svg',
            'imageSite' => 'nullable|media:jpg,png,gif,webp,svg',
            'rating' => 'nullable|float',
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
            'imageLogo' => trans('school::http.requests.admin.schoolCreateRequest.imageLogo'),
            'imageSite' => trans('school::http.requests.admin.schoolCreateRequest.imageSite'),
            'rating' => trans('school::http.requests.admin.schoolCreateRequest.rating'),
            'status' => trans('school::http.requests.admin.schoolCreateRequest.status'),
        ];
    }
}
