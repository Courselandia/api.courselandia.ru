<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Enums;

use App\Models\Enums\EnumLabel;

/**
 * Поисковая система.
 */
enum Engine: string implements EnumLabel
{
    /**
     * Yandex.
     */
    case YANDEX = 'yandex';

    /**
     * Google.
     */
    case GOOGLE = 'google';

    /**
     * Фековая поисковая система для тестирования.
     */
    case FAKE = 'fake';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::YANDEX => 'Yandex',
            self::GOOGLE => 'Google',
            self::FAKE => 'Fake',
        };
    }
}
