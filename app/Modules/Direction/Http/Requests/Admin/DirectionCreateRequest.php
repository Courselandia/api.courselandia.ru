<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape(['weight' => 'string', 'status' => 'string'])] public function rules(): array
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
    #[ArrayShape(['weight' => 'string', 'status' => 'string'])] public function attributes(): array
    {
        return [
            'weight' => trans('direction::http.requests.admin.directionCreateRequest.weight'),
            'status' => trans('direction::http.requests.admin.directionCreateRequest.status'),
        ];
    }
}
