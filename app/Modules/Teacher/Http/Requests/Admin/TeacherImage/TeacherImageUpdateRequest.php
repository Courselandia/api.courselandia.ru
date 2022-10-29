<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Requests\Admin\TeacherImage;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления изображения.
 */
class TeacherImageUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['image' => 'string'])] public function rules(): array
    {
        return [
            'image' => 'required|file|media:jpg,png,gif,webp',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['image' => 'string', 'type' => 'string'])] public function attributes(): array
    {
        return [
            'image' => trans('user::http.requests.admin.teacherImageUpdateRequest.image'),
        ];
    }
}
