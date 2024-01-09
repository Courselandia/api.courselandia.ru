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
 * Класс для авторизации пользователя.
 */
class AccessSignInRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'login' => 'required|between:1,199',
            'password' => 'required|between:4,25',
            'remember' => 'nullable|boolean'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    public function attributes(
    ): array
    {
        return [
            'login' => trans('access::http.requests.site.accessSignInRequest.login'),
            'password' => trans('access::http.requests.site.accessSignInRequest.password'),
            'remember' => trans('access::http.requests.site.accessSignInRequest.remember'),
        ];
    }
}
