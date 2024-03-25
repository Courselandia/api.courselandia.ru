<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Collection\Entities\Collection as CollectionEntity;
use App\Modules\Collection\Models\Collection;

/**
 * Класс действия для получения коллекции.
 */
class CollectionGetAction extends Action
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
     * @return CollectionEntity|null Вернет результаты исполнения.
     */
    public function run(): ?CollectionEntity
    {
        $cacheKey = Util::getKey('collection', $this->id);

        return Cache::tags(['catalog', 'collection'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $collection = Collection::where('id', $this->id)
                    ->with([
                        'metatag',
                        'direction',
                        'filters',
                        'analyzers',
                    ])
                    ->first();

                return $collection ? CollectionEntity::from($collection->toArray()) : null;
            }
        );
    }
}
