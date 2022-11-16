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
use JetBrains\PhpStorm\ArrayShape;
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
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
        'filters.rating' => 'string',
        'filters.price.*' => 'string',
        'filters.online' => 'string',
        'filters.employment' => 'string',
        'filters.duration.*' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('courses');
        $column[] = 'school-name';
        $column[] = 'directions-name';
        $column[] = 'professions-name';
        $column[] = 'categories-name';
        $column[] = 'skills-name';
        $column[] = 'teachers-name';
        $column[] = 'tools-name';
        $column[] = 'levels-name';
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $column . '|filter_date_range:published_at',
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
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
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
            'sorts' => trans('course::http.requests.admin.courseReadRequest.sorts'),
            'offset' => trans('course::http.requests.admin.courseReadRequest.offset'),
            'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
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
