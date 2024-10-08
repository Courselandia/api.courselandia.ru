<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\User;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\User\Enums\Role;

/**
 * Класс запрос для создания пользователя.
 */
class UserCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|file|media:jpg,png,gif,webp',
            'invitation' => 'nullable|boolean',
            'role' => 'required|in:' . implode(',', EnumList::getValues(Role::class)),
            'verified' => 'boolean',
            'two_factor' => 'boolean',
            'status' => 'boolean',
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
            'image' => trans('user::http.requests.admin.user.userCreateRequest.image'),
            'invitation' => trans('user::http.requests.admin.user.userCreateRequest.invitation'),
            'role' => trans('user::http.requests.admin.user.userCreateRequest.role'),
            'verified' => trans('user::http.requests.admin.user.userCreateRequest.verified'),
            'two_factor' => trans('user::http.requests.admin.user.userCreateRequest.twoFactor'),
            'status' => trans('user::http.requests.admin.user.userCreateRequest.status'),
        ];
    }
}
