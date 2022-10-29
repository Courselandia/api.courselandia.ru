<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Requests\Admin\Teacher;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения учителя.
 */
class TeacherReadRequest extends FormRequest
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
        $columnSorts = Schema::getColumnListing('teachers');
        $columnSorts = implode(',', $columnSorts);

        $columnFilters = [
            'id',
            'name',
            'status',
        ];
        $columnFilters = implode(',', $columnFilters);

        return [
            'sorts' => 'array|sorts:' . $columnSorts,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnFilters,
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
            'sorts' => trans('teacher::http.requests.admin.teacherReadRequest.sorts'),
            'start' => trans('teacher::http.requests.admin.teacherReadRequest.start'),
            'limit' => trans('teacher::http.requests.admin.teacherReadRequest.limit'),
            'filters' => trans('teacher::http.requests.admin.teacherReadRequest.filters'),
        ];
    }
}
