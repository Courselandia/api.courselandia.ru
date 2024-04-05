<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Site;

use Util;
use Cache;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Collection\Models\Collection;
use App\Modules\Collection\Entities\Collection as CollectionEntity;

/**
 * Класс действия для получения коллекции по ссылке.
 */
class CollectionLinkAction extends Action
{
    /**
     * Ссылка на коллекцию.
     *
     * @var string
     */
    public string $link;

    /**
     * @param string $link Ссылка на коллекцию.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return ?CollectionEntity Вернет коллекцию курсов.
     */
    public function run(): ?CollectionEntity
    {
        $cacheKey = Util::getKey(
            'collection',
            'site',
            'link',
            $this->link,
        );

        return Cache::tags(['catalog', 'collection'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $collection = Collection::active()
                    ->with([
                        'direction',
                        'metatag',
                        'courses',
                    ])
                    ->where('link', $this->link)
                    ->first();

                return $collection ? CollectionEntity::from($collection->toArray()) : null;
            }
        );
    }
}
