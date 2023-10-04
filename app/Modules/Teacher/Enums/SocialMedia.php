<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Enums;

/**
 * Социальные сети.
 */
enum SocialMedia: string
{
    case LINKED_ID = 'linkedIn';
    case FACEBOOK = 'facebook';
    case VK = 'vk';
    case OK = 'ok';
    case TELEGRAM = 'telegram';
    case WHATS_APP = 'whatsApp';
    case BEHANCE = 'behance';
    case DRIBBLE = 'dribble';
    case INSTAGRAM = 'instagram';
    case TWITTER = 'twitter';
    case DISCORD = 'discord';
    case YOU_TUBE = 'youTube';
    case TWITCH = 'twitch';
    case TIK_TOK = 'tikTok';
    case SITE = 'site';
    case VC = 'vc';
    case YANDEX_Q = 'yandex_q';
    case GITHUB = 'github';
    case GITLAB = 'gitlab';
    case SKYPE = 'skype';
    case YOUDO = 'youdo';
    case PINTEREST = 'pinterest';
    case YANDEX_DZEN = 'yandex_dzen';
    case HABR_CAREER = 'habr_career';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::LINKED_ID => 'LinkedIn',
            self::FACEBOOK => 'Facebook',
            self::VK => 'VK',
            self::OK => 'OK',
            self::TELEGRAM => 'Telegram',
            self::WHATS_APP => 'WhatsApp',
            self::BEHANCE => 'Behance',
            self::DRIBBLE => 'Dribble',
            self::INSTAGRAM => 'Instagram',
            self::TWITTER => 'Twitter',
            self::DISCORD => 'Discord',
            self::YOU_TUBE => 'YouTube',
            self::TWITCH => 'Twitch',
            self::TIK_TOK => 'TikTok',
            self::SITE => 'Личный сайт',
            self::VC => 'vc.ru',
            self::YANDEX_Q => 'Яндекс.Кью',
            self::GITHUB => 'Github',
            self::GITLAB => 'Gitlab',
            self::SKYPE => 'Skype',
            self::YOUDO => 'Youdo',
            self::PINTEREST => 'Pinterest',
            self::YANDEX_DZEN => 'Яндекс.Дзен',
            self::HABR_CAREER => 'Хабр Карьера',
        };
    }
}
