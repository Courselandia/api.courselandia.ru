<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Admin\PublicationImage;

use App\Models\FormRequest;

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
    public function rules(): array
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
    public function attributes(): array
    {
        return [
            'image' => trans('user::http.requests.admin.publicationImageUpdateRequest.image')
        ];
    }
}
