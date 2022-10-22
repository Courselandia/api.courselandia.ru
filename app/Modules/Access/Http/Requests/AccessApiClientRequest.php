<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс для генерации API клиента.
 */
class AccessApiClientRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['login' => 'string', 'password' => 'string', 'remember' => 'bool'])] public function rules(): array
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
    #[ArrayShape(['login' => 'string', 'password' => 'string', 'remember' => 'string'])] public function attributes(
    ): array
    {
        return [
            'login' => trans('access::http.requests.accessApiClientRequest.login'),
            'password' => trans('access::http.requests.accessApiClientRequest.password'),
            'remember' => trans('access::http.requests.accessApiClientRequest.remember'),
        ];
    }
}
