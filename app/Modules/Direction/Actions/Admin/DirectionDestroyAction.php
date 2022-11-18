<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Modules\Direction\Models\Direction;
use Cache;

/**
 * Класс действия для удаления направления.
 */
class DirectionDestroyAction extends Action
{
    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Direction::destroy($this->ids);

            Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();
        }

        return true;
    }
}
