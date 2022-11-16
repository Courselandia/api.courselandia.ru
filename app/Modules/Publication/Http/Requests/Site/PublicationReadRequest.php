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
 * Класс запрос для получения архива публикаций.
 */
class PublicationReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'year' => 'string',
        'limit' => 'string',
        'offset' => 'string',
        'path' => 'string'
    ])] public function rules(): array
    {
        return [
            'year' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'offset' => 'integer|digits_between:0,20',
            'path' => 'max:500',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'year' => 'string',
        'limit' => 'string',
        'offset' => 'string',
        'path' => 'string'
    ])] public function attributes(): array
    {
        return [
            'year' => trans('publication::http.requests.site.publicationReadRequest.year'),
            'limit' => trans('publication::http.requests.site.publicationReadRequest.limit'),
            'offset' => trans('publication::http.requests.site.publicationReadRequest.offset'),
            'path' => trans('publication::http.requests.site.publicationReadRequest.path'),
        ];
    }
}
