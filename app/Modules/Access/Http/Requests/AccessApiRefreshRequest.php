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
 * Класс для обновления API токена.
 */
class AccessApiRefreshRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['refreshToken' => 'string'])] public function rules(): array
    {
        return [
            'refreshToken' => 'required|string'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['refreshToken' => 'string'])] public function attributes(): array
    {
        return [
            'refreshToken' => trans('access::http.requests.accessApiRefreshRequest.refreshToken')
        ];
    }
}
