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
use Schema;

/**
 * Класс запрос для чтения направлений.
 */
class DirectionReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'start' => 'string',
        'limit' => 'string',
        'filters' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('directions');
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:'.$column,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:'.$column.'|filter_date_range:published_at',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'start' => 'string',
        'limit' => 'string',
        'filters' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('direction::http.requests.admin.directionReadRequest.sorts'),
            'start' => trans('direction::http.requests.admin.directionReadRequest.start'),
            'limit' => trans('direction::http.requests.admin.directionReadRequest.limit'),
            'filters' => trans('direction::http.requests.admin.directionReadRequest.filters'),
        ];
    }
}
