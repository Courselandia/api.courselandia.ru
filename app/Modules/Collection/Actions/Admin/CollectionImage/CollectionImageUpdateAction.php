<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\CollectionImage;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Collection\Actions\Admin\Collection\CollectionGetAction;
use App\Modules\Collection\Entities\Collection as CollectionEntity;
use App\Modules\Collection\Models\Collection;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения коллекции.
 */
class CollectionImageUpdateAction extends Action
{
    /**
     * ID коллекции.
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
     * @param int|string $id ID коллекции.
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
     * @return CollectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): CollectionEntity
    {
        if ($this->id) {
            $action = new CollectionGetAction($this->id);
            $collection = $action->run();

            if ($collection) {
                $collection = $collection->toArray();
                $collection['image_small_id'] = $this->image;
                $collection['image_middle_id'] = $this->image;
                $collection['image_big_id'] = $this->image;

                Collection::find($this->id)->update($collection);
                Cache::tags(['catalog', 'collection'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.collectionImageUpdateAction.notExistCollection'));
    }
}
