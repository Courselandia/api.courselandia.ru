<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests;

use App\Models\FormRequest;

/**
 * Класс для генерации API токена.
 */
class AccessApiTokenRequest extends FormRequest
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
    public function attributes(): array
    {
        return [
            'login' => trans('access::http.requests.accessApiTokenRequest.login'),
            'password' => trans('access::http.requests.accessApiTokenRequest.password'),
            'remember' => trans('access::http.requests.accessApiTokenRequest.remember'),
        ];
    }
}
