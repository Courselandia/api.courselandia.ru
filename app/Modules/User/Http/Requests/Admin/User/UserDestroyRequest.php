<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\User;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для удаления пользователя.
 */
class UserDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     */
    #[ArrayShape(['ids' => 'string'])] public function rules(): array
    {
        return [
            'ids' => 'required|json|ids'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['ids' => 'string'])] public function attributes(): array
    {
        return [
            'ids' => trans('user::http.requests.admin.user.userDestroyRequest.ids')
        ];
    }
}
