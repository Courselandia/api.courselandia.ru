<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Requests\Site;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Salary\Enums\Level;

/**
 * Класс запрос для получения раздела.
 */
class SectionLinkRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'links' => 'required|array',
            'links.*' => 'string',
            'level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'free' => 'boolean',
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
            'links' => trans('section::http.requests.site.sectionLinkRequest.links'),
            'links.*' => trans('section::http.requests.site.sectionLinkRequest.links'),
            'level' => trans('section::http.requests.site.sectionLinkRequest.level'),
            'free' => trans('section::http.requests.site.sectionLinkRequest.free'),
        ];
    }
}
