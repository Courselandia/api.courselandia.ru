<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\School;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания школ.
 */
class SchoolCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['imageLogo' => 'string', 'imageSite' => 'string'])] public function rules(): array
    {
        return [
            'imageLogo' => 'nullable|media:jpg,png,gif,webp,svg',
            'imageSite' => 'nullable|media:jpg,png,gif,webp,svg',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'imageLogo' => 'string',
        'imageSite' => 'string',
    ])] public function attributes(): array
    {
        return [
            'imageLogo' => trans('school::http.requests.admin.schoolCreateRequest.imageLogo'),
            'imageSite' => trans('school::http.requests.admin.schoolCreateRequest.imageSite'),
        ];
    }
}
