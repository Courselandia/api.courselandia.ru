<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Http\Requests\Admin;

use App\Models\FormRequest;

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
    public function rules(): array
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
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20'
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
            'sorts' => trans('log::http.requests.admin.logReadRequest.sorts'),
            'offset' => trans('log::http.requests.admin.logReadRequest.offset'),
            'limit' => trans('log::http.requests.admin.logReadRequest.limit'),
            'filters' => trans('log::http.requests.admin.logReadRequest.filters'),
        ];
    }
}
