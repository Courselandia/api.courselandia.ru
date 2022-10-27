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
    #[ArrayShape(['image' => 'string', 'published_at' => 'string'])] public function rules(): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp,svg',
            'published_at' => 'required|date_format:Y-m-d H:i:s O',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
        'published_at' => 'string',
    ])] public function attributes(): array
    {
        return [
            'image' => trans('school::http.requests.admin.schoolCreateRequest.image'),
            'published_at' => trans('school::http.requests.admin.schoolCreateRequest.publishedAt'),
        ];
    }
}
