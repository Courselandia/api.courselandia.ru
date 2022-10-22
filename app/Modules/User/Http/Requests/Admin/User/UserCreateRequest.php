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
use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape([
        'image' => 'string',
        'invitation' => 'string',
        'role' => 'string',
    ])] public function rules(): array
    {
        return [
            'image' => 'nullable|file|media:jpg,png,gif,webp',
            'invitation' => 'nullable|boolean',
            'role' => 'in:'.implode(',' , EnumList::getValues(Role::class))
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'image' => 'string',
        'invitation' => 'string',
        'role' => 'string'
    ])] public function attributes(): array
    {
        return [
            'image' => trans('user::http.requests.admin.user.userCreateRequest.image'),
            'invitation' => trans('user::http.requests.admin.user.userCreateRequest.invitation'),
            'role' => trans('user::http.requests.admin.user.userCreateRequest.role')
        ];
    }
}
