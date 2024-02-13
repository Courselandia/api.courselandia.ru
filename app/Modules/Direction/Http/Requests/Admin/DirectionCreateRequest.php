<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для создания направлений.
 */
class DirectionCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'weight' => 'integer',
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
            'weight' => trans('direction::http.requests.admin.directionCreateRequest.weight'),
            'status' => trans('direction::http.requests.admin.directionCreateRequest.status'),
        ];
    }
}
