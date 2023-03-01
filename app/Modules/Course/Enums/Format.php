<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Enums;

/**
 * Формат.
 */
enum Format: string
{
    /**
     * Рубли.
     */
    case ONLINE = 'online';

    /**
     * Доллар.
     */
    case OFFLINE = 'offline';
}
