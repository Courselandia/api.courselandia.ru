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
    public function rules(): array
    {
        $columnsSorts = Schema::getColumnListing('teachers');
        $columnsSorts = implode(',', $columnsSorts);

        $columnsFilters = [
            'id',
            'name',
            'status',
        ];
        $columnsFilters = implode(',', $columnsFilters);

        return [
            'sorts' => 'array|sorts:' . $columnsSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnsFilters,
            'filters.status' => 'boolean',
            'showPhoto' => 'boolean',
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
            'sorts' => trans('teacher::http.requests.admin.teacherReadRequest.sorts'),
            'offset' => trans('teacher::http.requests.admin.teacherReadRequest.offset'),
            'limit' => trans('teacher::http.requests.admin.teacherReadRequest.limit'),
            'filters' => trans('teacher::http.requests.admin.teacherReadRequest.filters'),
            'filters.status' => trans('teacher::http.requests.admin.teacherReadRequest.status'),
            'showPhoto' => trans('teacher::http.requests.admin.teacherReadRequest.showPhoto'),
        ];
    }
}
