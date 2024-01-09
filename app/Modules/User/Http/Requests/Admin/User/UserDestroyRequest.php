<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\User;

use App\Models\FormRequest;

/**
 * Класс запрос для удаления пользователя.
 */
class UserDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
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
            'ids' => trans('user::http.requests.admin.user.userDestroyRequest.ids')
        ];
    }
}
