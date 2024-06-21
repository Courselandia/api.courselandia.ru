<?php
/**
 * Фасады ядра.
 * Этот пакет содержит фасады ядра системы.
 *
 * @package App.Models.Facades
 */

namespace App\Models\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса измерения скорости кода.
 */
class Speed extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'speed';
    }
}
