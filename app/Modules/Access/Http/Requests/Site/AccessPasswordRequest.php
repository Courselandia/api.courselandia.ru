<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests\Site;

use App\Models\FormRequest;

/**
 * Класс для изменения пароля пользователя.
 */
class AccessPasswordRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'password_current' => 'required|between:4,25',
            'password' => 'required|between:4,25|confirmed',
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
            'password_current' => trans('access::http.requests.site.accessPasswordRequest.passwordCurrent'),
            'password' => trans('access::http.requests.site.accessPasswordRequest.password'),
        ];
    }
}
