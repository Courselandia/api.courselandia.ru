<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\SchoolImage;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для удаления изображения.
 */
class SchoolImageDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['type' => 'string'])] public function rules(): array
    {
        return [
            'type' => 'required|in:site,logo'
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
            'type' => trans('user::http.requests.admin.schoolImageUpdateRequest.type')
        ];
    }
}
