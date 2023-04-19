<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Enums;

use App\Models\Enums\EnumLabel;

enum Status: string implements EnumLabel
{
    /**
     * Активный.
     */
    case ACTIVE = 'active';

    /**
     * Неактивный.
     */
    case DISABLED = 'disabled';

    /**
     * Рассмотрение.
     */
    case REVIEW = 'review';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::ACTIVE => 'Активный',
            self::DISABLED => 'Отключен',
            self::REVIEW => 'В рассмотрении',
        };
    }
}
