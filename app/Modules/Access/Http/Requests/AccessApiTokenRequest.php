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
 * Класс для генерации API токена.
 */
class AccessApiTokenRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['secret' => 'string'])] public function rules(): array
    {
        return [
            'secret' => 'required|string'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['secret' => 'string'])] public function attributes(): array
    {
        return [
            'secret' => trans('access::http.requests.accessApiTokenRequest.secret')
        ];
    }
}
