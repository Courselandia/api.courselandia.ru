<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\SchoolImage;

use App\Models\FormRequest;

/**
 * Класс запрос для обновления изображения.
 */
class SchoolImageUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'image' => 'required|file|media:jpg,png,gif,webp',
            'type' => 'required|in:site,logo'
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
            'image' => trans('user::http.requests.admin.schoolImageUpdateRequest.image'),
            'type' => trans('user::http.requests.admin.schoolImageUpdateRequest.type')
        ];
    }
}
