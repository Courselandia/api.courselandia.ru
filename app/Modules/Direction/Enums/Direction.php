<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Enums;

enum Direction: int
{
    /**
     * Программирование.
     */
    case PROGRAMMING = 1;

    /**
     * Маркетинг.
     */
    case MARKETING = 2;

    /**
     * Дизайн.
     */
    case DESIGN = 3;

    /**
     * Бизнес и управление.
     */
    case BUSINESS = 4;

    /**
     * Аналитика.
     */
    case ANALYTICS = 5;

    /**
     * Игры.
     */
    case GAMES = 6;

    /**
     * Другие профессии.
     */
    case OTHER = 7;
}
