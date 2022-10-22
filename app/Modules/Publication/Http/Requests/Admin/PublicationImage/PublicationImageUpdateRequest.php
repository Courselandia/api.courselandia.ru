<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Admin\PublicationImage;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления изображения для пользователя
 */
class PublicationImageUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['image' => 'string'])] public function rules(): array
    {
        return [
            'image' => 'required|file|media:jpg,png,gif,webp'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['image' => 'string'])] public function attributes(): array
    {
        return [
            'image' => trans('user::http.requests.admin.publicationImageUpdateRequest.image')
        ];
    }
}
