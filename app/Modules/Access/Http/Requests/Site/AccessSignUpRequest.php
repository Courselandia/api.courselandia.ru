<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests\Site;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс для регистрации пользователя.
 */
class AccessSignUpRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'login' => 'string',
        'password' => 'string',
        'first_name' => 'string',
        'second_name' => 'string',
        'company' => 'string',
        'phone' => 'string'
    ])] public function rules(): array
    {
        return [
            'login' => 'required|between:1,199',
            'password' => 'required|between:4,25|confirmed',
            'first_name' => 'nullable|max:191',
            'second_name' => 'nullable|max:191',
            'company' => 'nullable|max:191',
            'phone' => 'nullable|phone:7'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'login' => 'string',
        'password' => 'string',
        'first_name' => 'string',
        'second_name' => 'string',
        'company' => 'string',
        'phone' => 'string'
    ])] public function attributes(): array
    {
        return [
            'login' => trans('access::http.requests.site.accessSignUpRequest.login'),
            'password' => trans('access::http.requests.site.accessSignUpRequest.password'),
            'first_name' => trans('access::http.requests.site.accessSignUpRequest.firstName'),
            'second_name' => trans('access::http.requests.site.accessSignUpRequest.secondName'),
            'company' => trans('access::http.requests.site.accessSignUpRequest.company'),
            'phone' => trans('access::http.requests.site.accessSignUpRequest.phone'),
        ];
    }
}
