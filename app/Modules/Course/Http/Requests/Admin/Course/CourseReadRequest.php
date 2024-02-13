<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Requests\Admin\Course;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Course\Enums\Status;
use Schema;

/**
 * Класс запрос для чтения категорий.
 */
class CourseReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('courses');

        $columnsSort = array_merge($columns,
            [
                'school-name',
                'directions-name',
                'professions-name',
                'categories-name',
                'skills-name',
                'teachers-name',
                'tools-name',
                'levels-name',
            ]
        );

        $columnsFilter = array_merge(
            $columns,
            [
                'school-id',
                'directions-id',
                'professions-id',
                'categories-id',
                'skills-id',
                'teachers-id',
                'tools-id',
                'levels-id',
            ]
        );

        return [
            'sorts' => 'array|sorts:' . implode(',', $columnsSort),
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:published_at',
            'filters.status.*' => 'in:' . implode(',', EnumList::getValues(Status::class)),
            'filters.rating' => 'nullable|float',
            'filters.price.*' => 'nullable|float',
            'filters.online' => 'boolean',
            'filters.employment' => 'boolean',
            'filters.duration.*' => 'integer',
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
            'sorts' => trans('course::http.requests.admin.courseReadRequest.sorts'),
            'offset' => trans('course::http.requests.admin.courseReadRequest.offset'),
            'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
            'filters' => trans('course::http.requests.admin.courseReadRequest.filters'),
            'filters.status.*' => trans('course::http.requests.admin.courseReadRequest.status'),
            'filters.rating' => trans('course::http.requests.admin.courseReadRequest.rating'),
            'filters.price.*' => trans('course::http.requests.admin.courseReadRequest.price'),
            'filters.online' => trans('course::http.requests.admin.courseReadRequest.online'),
            'filters.employment' => trans('course::http.requests.admin.courseReadRequest.employment'),
            'filters.duration.*' => trans('course::http.requests.admin.courseReadRequest.duration'),
        ];
    }
}
