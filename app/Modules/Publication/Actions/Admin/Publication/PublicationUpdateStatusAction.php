<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;

/**
 * Класс действия для обновления статуса публикаций.
 */
class PublicationUpdateStatusAction extends Action
{
    /**
     * ID публикации.
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
     * @param int|string $id ID публикации.
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
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): PublicationEntity
    {
        $action = new PublicationGetAction($this->id);
        $publicationEntity = $action->run();

        if ($publicationEntity) {
            $publicationEntity->status = $this->status;
            Publication::find($this->id)->update($publicationEntity->toArray());
            Cache::tags(['publication'])->flush();

            return $publicationEntity;
        }

        throw new RecordNotExistException(
            trans('publication::actions.admin.publicationUpdateStatusAction.notExistPublication')
        );
    }
}
