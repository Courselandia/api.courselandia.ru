<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Salary\Enums\Level;

/**
 * Класс запрос для создания раздела.
 */
class SectionCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'status' => 'boolean',
            'level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'items' => 'array',
            'items.*' => 'array:id,type',
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
            'status' => trans('section::http.requests.admin.sectionCreateRequest.status')
        ];
    }
}
