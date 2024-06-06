<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Managers;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы виджетов.
 */
class WidgetManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('widget.driver');
    }
}
