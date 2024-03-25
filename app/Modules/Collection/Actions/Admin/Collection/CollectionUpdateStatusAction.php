<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Collection\Entities\Collection as CollectionEntity;
use App\Modules\Collection\Models\Collection;

/**
 * Класс действия для обновления статуса коллекции.
 */
class CollectionUpdateStatusAction extends Action
{
    /**
     * ID коллекции.
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
     * @param int|string $id ID коллекции.
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
     * @return CollectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): CollectionEntity
    {
        $action = new CollectionGetAction($this->id);
        $collectionEntity = $action->run();

        if ($collectionEntity) {
            $collectionEntity->status = $this->status;
            Collection::find($this->id)->update($collectionEntity->toArray());
            Cache::tags(['catalog', 'collection'])->flush();

            return $collectionEntity;
        }

        throw new RecordNotExistException(
            trans('collection::actions.admin.collectionUpdateStatusAction.notExistCollection')
        );
    }
}
