<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Enums;

/**
 * Валюта.
 */
enum SocialMedia: string
{
    /**
     * Telegram.
     */
    case TELEGRAM = 'telegram';

    /**
     * Telegram.
     */
    case FACEBOOK = 'facebook';
}
