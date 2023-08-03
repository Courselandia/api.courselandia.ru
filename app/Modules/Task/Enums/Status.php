<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Enums;

use App\Models\Enums\EnumLabel;

/**
 * Статус задания.
 */
enum Status: string implements EnumLabel
{
    /**
     * Ожидает.
     */
    case WAITING = 'waiting';

    /**
     * Исполнение.
     */
    case PROCESSING = 'processing';

    /**
     * Завершен.
     */
    case FINISHED = 'finished';

    /**
     * Ошибка.
     */
    case FAILED = 'failed';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::WAITING => 'Ожидает',
            self::PROCESSING => 'Исполнение',
            self::FINISHED => 'Завершен',
            self::FAILED => 'Ошибка',
        };
    }
}
