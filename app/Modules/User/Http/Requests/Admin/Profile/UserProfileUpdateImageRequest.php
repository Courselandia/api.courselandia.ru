<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\Profile;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления профиля пользователя.
 */
class UserProfileUpdateImageRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'image' => 'string',
    ])] public function rules(): array
    {
        return [
            'image' => 'required|file|media:jpg,png,gif,webp',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
    ])] public function attributes(): array
    {
        return [
            'image' => trans('user::http.requests.admin.user.userProfileUpdateRequest.image'),
        ];
    }
}
