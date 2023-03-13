<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Requests\Site\Course;

use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для чтения категорий.
 */
class CourseReadRequest extends CourseFilterItemReadRequest
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
        'openedSchools' => 'string',
        'openedCategories' => 'string',
        'openedProfessions' => 'string',
        'openedTeachers' => 'string',
        'openedSkills' => 'string',
        'openedTools' => 'string',
    ])] public function rules(): array
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
                'relevance'
            ]
        );

        return array_merge(parent::rules(),
            [
                'sorts' => 'array|sorts:' . implode(',', $columnsSort),
                'offset' => 'integer|digits_between:0,20',
                'limit' => 'integer|digits_between:0,20',
                'openedSchools' => 'boolean',
                'openedCategories' => 'boolean',
                'openedProfessions' => 'boolean',
                'openedTeachers' => 'boolean',
                'openedSkills' => 'boolean',
                'openedTools' => 'boolean',
            ]
        );
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
        'openedSchools' => 'string',
        'openedCategories' => 'string',
        'openedProfessions' => 'string',
        'openedTeachers' => 'string',
        'openedSkills' => 'string',
        'openedTools' => 'string',
    ])] public function attributes(): array
    {
        return array_merge(parent::rules(),
            [
                'sorts' => trans('course::http.requests.admin.courseReadRequest.sorts'),
                'offset' => trans('course::http.requests.admin.courseReadRequest.offset'),
                'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
                'section' => trans('course::http.requests.admin.courseReadRequest.section'),
                'sectionLink' => trans('course::http.requests.admin.courseReadRequest.sectionLink'),
                'openedSchools' => trans('course::http.requests.admin.courseReadRequest.openedSchools'),
                'openedCategories' => trans('course::http.requests.admin.courseReadRequest.openedCategories'),
                'openedProfessions' => trans('course::http.requests.admin.courseReadRequest.openedProfessions'),
                'openedTeachers' => trans('course::http.requests.admin.courseReadRequest.openedTeachers'),
                'openedSkills' => trans('course::http.requests.admin.courseReadRequest.openedSkills'),
                'openedTools' => trans('course::http.requests.admin.courseReadRequest.openedTools'),
            ]
        );
    }
}
