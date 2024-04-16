<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\CollectionImage;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Collection\Models\Collection;
use Cache;
use ImageStore;
use ReflectionException;
use Util;

/**
 * Класс действия для удаления изображения коллекции.
 */
class CollectionImageDestroyAction extends Action
{
    /**
     * ID коллекции.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID коллекции.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ReflectionException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('collection', 'model', $this->id);

        $collection = Cache::tags(['catalog', 'collection'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return Collection::find($this->id);
            }
        );

        if ($collection) {
            if ($collection->image_small_id) {
                ImageStore::destroy($collection->image_small_id->id);
            }

            if ($collection->image_middle_id) {
                ImageStore::destroy($collection->image_middle_id->id);
            }

            if ($collection->image_big_id) {
                ImageStore::destroy($collection->image_big_id->id);
            }

            $collection->image_small_id = null;
            $collection->image_middle_id = null;
            $collection->image_big_id = null;

            $collection->save();
            Cache::tags(['catalog', 'collection'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('collection::actions.admin.collectionImageDestroyAction.notExistCollection')
        );
    }
}
