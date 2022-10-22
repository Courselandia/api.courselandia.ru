<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\PublicationImage;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Publication\Actions\Admin\Publication\PublicationGetAction;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Repositories\Publication;
use Cache;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Обновление изображения публикации.
 */
class PublicationImageUpdateAction extends Action
{
    /**
     * Репозиторий публикации.
     *
     * @var Publication
     */
    private Publication $publication;

    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Конструктор.
     *
     * @param  Publication  $publication  Репозиторий публикации.
     */
    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): PublicationEntity
    {
        if ($this->id) {
            $action = app(PublicationGetAction::class);
            $action->id = $this->id;
            $publication = $action->run();

            if ($publication) {
                $publication->image_small_id = $this->image;
                $publication->image_middle_id = $this->image;
                $publication->image_big_id = $this->image;
                $this->publication->update($this->id, $publication);
                Cache::tags(['publication'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.publicationImageUpdateAction.notExistPublication'));
    }
}
