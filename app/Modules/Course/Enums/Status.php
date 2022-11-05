<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Enums;

use App\Models\Enums\EnumLabel;

/**
 * Статус.
 */
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
     * Черновик.
     */
    case DRAFT = 'draft';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::ACTIVE => 'Активный',
            self::DISABLED => 'Неактивный',
            self::DRAFT => 'Черновик',
        };
    }
}
