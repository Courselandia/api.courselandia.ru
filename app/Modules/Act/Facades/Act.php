<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса запоминания действий пользователя.
 */
class Act extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'act';
    }
}
