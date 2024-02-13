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
 * Класс запрос для удаления изображения.
 */
class SchoolImageDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
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
    public function attributes(): array
    {
        return [
            'type' => trans('user::http.requests.admin.schoolImageUpdateRequest.type')
        ];
    }
}
