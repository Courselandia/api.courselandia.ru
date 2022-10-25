<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Modules\Direction\Repositories\Direction;
use Cache;

/**
 * Класс действия для удаления направления.
 */
class DirectionDestroyAction extends Action
{
    /**
     * Репозиторий направлений.
     *
     * @var Direction
     */
    private Direction $direction;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Конструктор.
     *
     * @param  Direction  $direction  Репозиторий направлений.
     */
    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->direction->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();
        }

        return true;
    }
}
