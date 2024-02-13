<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Requests\Site\School;

use Schema;
use App\Models\FormRequest;

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
    public function rules(): array
    {
        $columnsSorts = Schema::getColumnListing('schools');
        $columnsSorts = implode(',', $columnsSorts);

        return [
            'sorts' => 'array|sorts:' . $columnsSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
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
            'sorts' => trans('school::http.requests.site.schoolReadRequest.sorts'),
            'offset' => trans('school::http.requests.site.schoolReadRequest.offset'),
            'limit' => trans('school::http.requests.site.schoolReadRequest.limit'),
        ];
    }
}
