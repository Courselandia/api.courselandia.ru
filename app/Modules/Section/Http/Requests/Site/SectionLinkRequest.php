<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Requests\Site;

use Config;
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
        $types = array_keys(Config::get('section.items'));

        return [
            'items' => 'required|array',
            'items.*.link' => 'required',
            'items.*.type' => 'required|in:' . implode(',', $types),
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
            'items' => trans('section::http.requests.site.sectionLinkRequest.items'),
            'items.*' => trans('section::http.requests.site.sectionLinkRequest.items'),
            'items.*.link' => trans('section::http.requests.site.sectionLinkRequest.link'),
            'items.*.type' => trans('section::http.requests.site.sectionLinkRequest.type'),
            'level' => trans('section::http.requests.site.sectionLinkRequest.level'),
            'free' => trans('section::http.requests.site.sectionLinkRequest.free'),
        ];
    }
}
