<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Admin\Publication;

/**
 * Класс запрос для создания публикаций.
 */
class PublicationUpdateRequest extends PublicationCreateRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(
    ): array
    {
        return [
            'image' => 'nullable|media:jpg,png,gif,webp',
            'published_at' => 'required|date_format:Y-m-d H:i:s O',
            'status' => 'required|boolean',
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
            'image' => trans('publication::http.requests.admin.publicationCreateRequest.image'),
            'published_at' => trans('publication::http.requests.admin.publicationCreateRequest.publishedAt'),
            'status' => trans('publication::http.requests.admin.publicationCreateRequest.status'),
        ];
    }
}
