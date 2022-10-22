<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\Config;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления конфигурации пользователя.
 */
class UserConfigUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'configs' => 'string',
    ])] public function rules(): array
    {
        return [
            'configs' => 'required|json',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'configs' => 'string',
    ])] public function attributes(): array
    {
        return [
            'configs' => trans('user::http.requests.admin.config.userConfigUpdateRequest.configs'),
        ];
    }
}
