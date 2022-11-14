<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Review\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания отзывов.
 */
class ReviewCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'school_id' => 'string',
        'course_id' => 'string',
        'status' => 'string',
        'rating' => 'string',
    ])] public function rules(): array
    {
        return [
            'school_id' => 'exists_soft:schools,id',
            'course_id' => 'nullable|exists_soft:courses,id',
            'status' => 'required|in:' . implode(',', EnumList::getValues(Status::class)),
            'rating' => 'integer',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'school_id' => 'string',
        'course_id' => 'string',
        'status' => 'string',
        'rating' => 'string'
    ])] public function attributes(): array
    {
        return [
            'school_id' => trans('review::http.requests.admin.reviewCreateRequest.schoolId'),
            'course_id' => trans('review::http.requests.admin.reviewCreateRequest.courseId'),
            'status' => trans('review::http.requests.admin.reviewCreateRequest.status'),
            'rating' => trans('review::http.requests.admin.reviewCreateRequest.rating'),
        ];
    }
}
