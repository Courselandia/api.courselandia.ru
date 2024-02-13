<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Requests\Site\Course;

/**
 * Класс запрос для чтения категорий.
 */
class CourseReadSearchRequest extends CourseFilterItemReadRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'limit' => 'integer|digits_between:0,20',
            ]
        );
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    public function attributes(): array
    {
        return array_merge(parent::rules(),
            [
                'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
            ]
        );
    }
}
