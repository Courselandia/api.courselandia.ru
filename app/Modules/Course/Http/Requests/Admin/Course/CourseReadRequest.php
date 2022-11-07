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
use App\Modules\Course\Enums\Duration;
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
        'start' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $column = Schema::getColumnListing('categories');
        $column = implode(',', $column);

        return [
            'sorts' => 'array|sorts:' . $column,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $column . '|filter_date_range:published_at',
            'filters.status' => 'in:' . implode(',', EnumList::getValues(Duration::class)),
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
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('course::http.requests.admin.courseReadRequest.sorts'),
            'start' => trans('course::http.requests.admin.courseReadRequest.start'),
            'limit' => trans('course::http.requests.admin.courseReadRequest.limit'),
            'filters' => trans('course::http.requests.admin.courseReadRequest.filters'),
            'filters.status' => trans('course::http.requests.admin.courseReadRequest.status'),
        ];
    }
}
