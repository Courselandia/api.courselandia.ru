<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Http\Requests\Admin;

use Schema;
use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Task\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения заданий.
 */
class TaskReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $columns = Schema::getColumnListing('tasks');

        $columnsFilter = array_merge($columns,
            [
                'user-id',
            ]
        );

        $columnsSort = array_merge($columns,
            [
                'user-name',
            ]
        );

        return [
            'sorts' => 'array|sorts:' . implode(',', $columnsSort),
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:launched_at|filter_date_range:finished_at|filter_date_range:created_at',
            'filters.status' => 'in:' . implode(',', EnumList::getValues(Status::class)),
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('task::http.requests.admin.taskReadRequest.sorts'),
            'offset' => trans('task::http.requests.admin.taskReadRequest.offset'),
            'limit' => trans('task::http.requests.admin.taskReadRequest.limit'),
            'filters' => trans('task::http.requests.admin.taskReadRequest.filters'),
            'filters.status' => trans('task::http.requests.admin.taskReadRequest.status'),
        ];
    }
}
