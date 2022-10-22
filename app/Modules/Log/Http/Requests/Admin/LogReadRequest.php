<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения логов.
 */
class LogReadRequest extends FormRequest
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
        'filters' => 'string'
    ])] public function rules(): array
    {
        $columnsSorts = [
            'id',
            'message',
            'channel',
            'level',
            'level_name',
            'unix_time',
            'datetime',
            'extra',
            'created_at',
            'updated_at',
        ];

        $columnsSorts = implode(',', $columnsSorts);

        $columnFilters = [
            'id',
            'message',
            'level_name',
            'created_at',
        ];
        $columnFilters = implode(',', $columnFilters);

        return [
            'sorts' => 'array|sorts:'.$columnsSorts,
            'filters' => 'array|filters:'.$columnFilters.'|filter_date:datetime',
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20'
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
        'filters' => 'string'
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('log::http.requests.admin.logReadRequest.sorts'),
            'start' => trans('log::http.requests.admin.logReadRequest.start'),
            'limit' => trans('log::http.requests.admin.logReadRequest.limit'),
            'filters' => trans('log::http.requests.admin.logReadRequest.filters'),
        ];
    }
}
