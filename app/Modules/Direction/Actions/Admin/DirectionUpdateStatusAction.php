<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Repositories\Direction;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса направлений.
 */
class DirectionUpdateStatusAction extends Action
{
    /**
     * Репозиторий направлений.
     *
     * @var Direction
     */
    private Direction $direction;

    /**
     * ID направления.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * @return DirectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): DirectionEntity
    {
        $action = app(DirectionGetAction::class);
        $action->id = $this->id;
        $directionEntity = $action->run();

        if ($directionEntity) {
            $directionEntity->status = $this->status;
            $this->direction->update($this->id, $directionEntity);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            return $directionEntity;
        }

        throw new RecordNotExistException(
            trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
        );
    }
}
