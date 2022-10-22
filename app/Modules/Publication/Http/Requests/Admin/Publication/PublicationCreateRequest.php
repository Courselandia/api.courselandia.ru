<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Admin\Publication;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания публикаций.
 */
class PublicationCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['image' => 'string', 'published_at' => 'string'])] public function rules(): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp,svg',
            'published_at' => 'required|date_format:Y-m-d H:i:s O',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
        'published_at' => 'string',
    ])] public function attributes(): array
    {
        return [
            'image' => trans('publication::http.requests.admin.publicationCreateRequest.image'),
            'published_at' => trans('publication::http.requests.admin.publicationCreateRequest.publishedAt'),
        ];
    }
}
