<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Requests\Site\Course;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Course\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для получения элементов фильтра.
 */
class CourseFilterItemReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'offset' => 'string',
        'limit' => 'string',
        'withCategories' => 'string',
        'withCount' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
        'filters.rating' => 'string',
        'filters.price.*' => 'string',
        'filters.online' => 'string',
        'filters.employment' => 'string',
        'filters.duration.*' => 'string',
    ])] public function rules(): array
    {
        $columns = Schema::getColumnListing('courses');
        $columnsFilter = array_merge(
            $columns,
            [
                'school-id',
                'school-link',
                'directions-id',
                'directions-link',
                'professions-id',
                'professions-link',
                'categories-id',
                'categories-link',
                'skills-id',
                'skills-link',
                'teachers-id',
                'teachers-link',
                'tools-id',
                'tools-link',
                'levels-id',
                'search',
                'levels-level',
                'credit',
                'free',
            ]
        );

        return [
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'withCategories' => 'boolean',
            'withCount' => 'boolean',
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:published_at',
            'filters.status' => 'in:' . implode(',', EnumList::getValues(Status::class)),
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
    #[ArrayShape([
        'offset' => 'string',
        'limit' => 'string',
        'withCategories' => 'string',
        'withCount' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
        'filters.rating' => 'string',
        'filters.price.*' => 'string',
        'filters.online' => 'string',
        'filters.employment' => 'string',
        'filters.duration.*' => 'string',
    ])] public function attributes(): array
    {
        return [
            'offset' => trans('course::http.requests.admin.courseReadRequest.offset'),
            'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
            'withCategories' => trans('course::http.requests.admin.courseReadRequest.withCategories'),
            'withCount' => trans('course::http.requests.admin.courseReadRequest.withCount'),
            'filters' => trans('course::http.requests.admin.courseReadRequest.filters'),
            'filters.status' => trans('course::http.requests.admin.courseReadRequest.status'),
            'filters.rating' => trans('course::http.requests.admin.courseReadRequest.rating'),
            'filters.price.*' => trans('course::http.requests.admin.courseReadRequest.price'),
            'filters.online' => trans('course::http.requests.admin.courseReadRequest.online'),
            'filters.employment' => trans('course::http.requests.admin.courseReadRequest.employment'),
            'filters.duration.*' => trans('course::http.requests.admin.courseReadRequest.duration'),
        ];
    }
}
