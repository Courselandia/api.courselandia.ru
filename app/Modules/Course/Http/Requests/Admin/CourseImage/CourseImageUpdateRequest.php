<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Requests\Admin\CourseImage;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления изображения для пользователя
 */
class CourseImageUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['image' => 'string'])] public function rules(): array
    {
        return [
            'image' => 'required|file|media:jpg,png,gif,webp'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['image' => 'string'])] public function attributes(): array
    {
        return [
            'image' => trans('course::http.requests.admin.courseImageUpdateRequest.image')
        ];
    }
}
