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
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Salary\Enums\Level;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания категории.
 */
class CourseCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'image' => 'string',
        'school_id' => 'string',
        'directions' => 'string',
        'directions.*' => 'string',
        'professions' => 'string',
        'professions.*' => 'string',
        'categories' => 'string',
        'categories.*' => 'string',
        'skills' => 'string',
        'skills.*' => 'string',
        'teachers' => 'string',
        'teachers.*' => 'string',
        'tools' => 'string',
        'tools.*' => 'string',
        'processes' => 'string',
        'processes.*' => 'string',
        'levels' => 'string',
        'levels.*' => 'string',
        'learns' => 'string',
        'employments' => 'string',
        'employments.*' => 'string',
        'features' => 'string',
        'features.*' => 'string',
        'language' => 'string',
        'rating' => 'string',
        'price' => 'string',
        'price_old' => 'string',
        'price_recurrent' => 'string',
        'currency' => 'string',
        'online' => 'string',
        'employment' => 'string',
        'duration' => 'string',
        'duration_unit' => 'duration_unit',
        'lessons_amount' => 'string',
        'modules_amount' => 'string',
        'program' => 'string',
        'status' => 'string',
    ])] public function rules(): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp,svg',
            'school_id' => 'exists_soft:schools,id',
            'directions' => 'array',
            'directions.*' => 'exists_soft:directions,id',
            'professions' => 'array',
            'professions.*' => 'exists_soft:professions,id',
            'categories' => 'array',
            'categories.*' => 'exists_soft:categories,id',
            'skills' => 'array',
            'skills.*' => 'exists_soft:skills,id',
            'teachers' => 'array',
            'teachers.*' => 'exists_soft:teachers,id',
            'tools' => 'array',
            'tools.*' => 'exists_soft:tools,id',
            'processes' => 'array',
            'processes.*' => 'exists_soft:processes,id',
            'levels' => 'array',
            'levels.*' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'learns' => 'array',
            'employments' => 'array',
            'employments.*' => 'exists_soft:employments,id',
            'features' => 'array',
            'features.*' => 'array:icon,text',
            'language' => 'nullable|in:' . implode(',', EnumList::getValues(Language::class)),
            'rating' => 'nullable|float',
            'price' => 'nullable|float',
            'price_old' => 'nullable|float',
            'price_recurrent' => 'nullable|float',
            'currency' => 'nullable|in:' . implode(',', EnumList::getValues(Currency::class)),
            'online' => 'nullable|boolean',
            'employment' => 'nullable|boolean',
            'duration' => 'nullable|integer',
            'duration_unit' => 'nullable|in:' . implode(',', EnumList::getValues(Duration::class)),
            'lessons_amount' => 'nullable|integer',
            'modules_amount' => 'nullable|integer',
            'program' => 'json',
            'status' => 'required|in:' . implode(',', EnumList::getValues(Status::class)),
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
        'school_id' => 'string',
        'directions' => 'string',
        'directions.*' => 'string',
        'professions' => 'string',
        'professions.*' => 'string',
        'categories' => 'string',
        'categories.*' => 'string',
        'skills' => 'string',
        'skills.*' => 'string',
        'teachers' => 'string',
        'teachers.*' => 'string',
        'tools' => 'string',
        'tools.*' => 'string',
        'processes' => 'string',
        'processes.*' => 'string',
        'levels' => 'string',
        'levels.*' => 'string',
        'learns' => 'string',
        'employments' => 'string',
        'employments.*' => 'string',
        'features' => 'string',
        'features.*' => 'string',
        'language' => 'string',
        'rating' => 'string',
        'price' => 'string',
        'price_old' => 'string',
        'price_recurrent' => 'string',
        'currency' => 'string',
        'online' => 'string',
        'employment' => 'string',
        'duration' => 'string',
        'duration_unit' => 'string',
        'lessons_amount' => 'string',
        'modules_amount' => 'string',
        'program' => 'string',
        'status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'image' => trans('course::http.requests.admin.courseCreateRequest.image'),
            'school_id' => trans('course::http.requests.admin.courseCreateRequest.schoolId'),
            'directions' => trans('course::http.requests.admin.courseCreateRequest.directions'),
            'directions.*' => trans('course::http.requests.admin.courseCreateRequest.directions'),
            'professions' => trans('course::http.requests.admin.courseCreateRequest.professions'),
            'professions.*' => trans('course::http.requests.admin.courseCreateRequest.professions'),
            'categories' => trans('course::http.requests.admin.courseCreateRequest.categories'),
            'categories.*' => trans('course::http.requests.admin.courseCreateRequest.categories'),
            'skills' => trans('course::http.requests.admin.courseCreateRequest.skills'),
            'skills.*' => trans('course::http.requests.admin.courseCreateRequest.skills'),
            'teachers' => trans('course::http.requests.admin.courseCreateRequest.teachers'),
            'teachers.*' => trans('course::http.requests.admin.courseCreateRequest.teachers'),
            'tools' => trans('course::http.requests.admin.courseCreateRequest.tools'),
            'tools.*' => trans('course::http.requests.admin.courseCreateRequest.tools'),
            'processes' => trans('course::http.requests.admin.courseCreateRequest.processes'),
            'processes.*' => trans('course::http.requests.admin.courseCreateRequest.processes'),
            'levels' => trans('course::http.requests.admin.courseCreateRequest.levels'),
            'levels.*' => trans('course::http.requests.admin.courseCreateRequest.levels'),
            'learns' => trans('course::http.requests.admin.courseCreateRequest.learns'),
            'employments' => trans('course::http.requests.admin.courseCreateRequest.employments'),
            'employments.*' => trans('course::http.requests.admin.courseCreateRequest.employments'),
            'features' => trans('course::http.requests.admin.courseCreateRequest.features'),
            'features.*' => trans('course::http.requests.admin.courseCreateRequest.features'),
            'language' => trans('course::http.requests.admin.courseCreateRequest.language'),
            'rating' => trans('course::http.requests.admin.courseCreateRequest.rating'),
            'price' => trans('course::http.requests.admin.courseCreateRequest.price'),
            'price_old' => trans('course::http.requests.admin.courseCreateRequest.priceOld'),
            'price_recurrent' => trans('course::http.requests.admin.courseCreateRequest.priceRecurrentPrice'),
            'currency' => trans('course::http.requests.admin.courseCreateRequest.currency'),
            'online' => trans('course::http.requests.admin.courseCreateRequest.online'),
            'employment' => trans('course::http.requests.admin.courseCreateRequest.employment'),
            'duration' => trans('course::http.requests.admin.courseCreateRequest.duration'),
            'duration_unit' => trans('course::http.requests.admin.courseCreateRequest.durationUnit'),
            'lessons_amount' => trans('course::http.requests.admin.courseCreateRequest.lessonsAmount'),
            'modules_amount' => trans('course::http.requests.admin.courseCreateRequest.modulesAmount'),
            'program' => trans('course::http.requests.admin.courseCreateRequest.program'),
            'status' => trans('course::http.requests.admin.courseCreateRequest.status'),
        ];
    }
}
