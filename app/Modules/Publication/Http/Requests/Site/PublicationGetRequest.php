<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Site;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для получения публикаций.
 */
class PublicationGetRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['id' => 'string', 'link' => 'string'])] public function rules(): array
    {
        return [
            'id' => 'integer|digits_between:0,20',
            'link' => 'max:191',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['id' => 'string', 'link' => 'string'])] public function attributes(): array
    {
        return [
            'id' => trans('publication::http.requests.site.publicationGetRequest.id'),
            'link' => trans('publication::http.requests.site.publicationGetRequest.link'),
        ];
    }
}
