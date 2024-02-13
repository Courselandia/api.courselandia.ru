<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use Cache;

/**
 * Класс действия для обновления статуса направлений.
 */
class DirectionUpdateStatusAction extends Action
{
    /**
     * ID направления.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param int|string $id ID направления.
     * @param bool $status Статус.
     */
    public function __construct(int|string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): DirectionEntity
    {
        $action = new DirectionGetAction($this->id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            $directionEntity->status = $this->status;

            Direction::find($this->id)->update($directionEntity->toArray());
            Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();

            return $directionEntity;
        }

        throw new RecordNotExistException(
            trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
        );
    }
}
