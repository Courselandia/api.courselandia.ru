<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Admin\School;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения школ.
 */
class SchoolReadRequest extends FormRequest
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
        $columnSorts = Schema::getColumnListing('schools');
        $columnSorts = implode(',', $columnSorts);

        $columnFilters = [
            'id',
            'published_at',
            'header',
            'link',
            'anons',
            'article',
            'status',
        ];
        $columnFilters = implode(',', $columnFilters);

        return [
            'sorts' => 'array|sorts:'.$columnSorts,
            'start' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:'.$columnFilters.'|filter_date_range:published_at',
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
            'sorts' => trans('school::http.requests.admin.schoolReadRequest.sorts'),
            'start' => trans('school::http.requests.admin.schoolReadRequest.start'),
            'limit' => trans('school::http.requests.admin.schoolReadRequest.limit'),
            'filters' => trans('school::http.requests.admin.schoolReadRequest.filters'),
        ];
    }
}
