<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
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
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): PublicationEntity
    {
        $action = app(PublicationGetAction::class);
        $action->id = $this->id;
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
