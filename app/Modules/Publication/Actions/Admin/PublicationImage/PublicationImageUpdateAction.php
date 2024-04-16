<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\PublicationImage;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Publication\Actions\Admin\Publication\PublicationGetAction;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения публикации.
 */
class PublicationImageUpdateAction extends Action
{
    /**
     * ID публикации.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Изображение.
     *
     * @var UploadedFile
     */
    private UploadedFile $image;

    /**
     * @param int|string $id ID публикации.
     * @param UploadedFile $image Изображение.
     */
    public function __construct(int|string $id, UploadedFile $image)
    {
        $this->id = $id;
        $this->image = $image;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): PublicationEntity
    {
        if ($this->id) {
            $action = new PublicationGetAction($this->id);
            $publication = $action->run();

            if ($publication) {
                Publication::find($this->id)->update([
                    ...$publication->toArray(),
                    'image_small_id' => $this->image,
                    'image_middle_id' => $this->image,
                    'image_big_id' => $this->image,
                ]);

                Cache::tags(['publication'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.publicationImageUpdateAction.notExistPublication'));
    }
}
