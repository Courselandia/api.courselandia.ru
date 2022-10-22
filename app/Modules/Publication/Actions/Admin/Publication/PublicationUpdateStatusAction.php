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
use App\Modules\Publication\Repositories\Publication;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса публикаций.
 */
class PublicationUpdateStatusAction extends Action
{
    /**
     * Репозиторий публикаций.
     *
     * @var Publication
     */
    private Publication $publication;

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
     * Конструктор.
     *
     * @param  Publication  $publication  Репозиторий публикаций.
     */
    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): PublicationEntity
    {
        $action = app(PublicationGetAction::class);
        $action->id = $this->id;
        $publicationEntity = $action->run();

        if ($publicationEntity) {
            $publicationEntity->status = $this->status;
            $this->publication->update($this->id, $publicationEntity);
            Cache::tags(['publication'])->flush();

            return $publicationEntity;
        }

        throw new RecordNotExistException(
            trans('publication::actions.admin.publicationUpdateStatusAction.notExistPublication')
        );
    }
}
