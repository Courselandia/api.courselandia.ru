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
 * Класс действия для получения коллекций.
 */
class CollectionReadAction extends Action
{
    /**
     * Фильтр по ID направлению.
     *
     * @var int|string|null
     */
    public int|string|null $direction_id = null;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * @param int|string|null $direction_id Фильтр по ID направлению.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки.
     */
    public function __construct(int|string|null $direction_id = null, int $offset = null, int $limit = null)
    {
        $this->direction_id = $direction_id;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результат исполнения.
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'collection',
            'site',
            'read',
            'count',
            $this->direction_id ?: 'all',
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['catalog', 'collection'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Collection::active()
                    ->select([
                        'id',
                        'metatag_id',
                        'direction_id',
                        'name',
                        'link',
                        'additional',
                        'amount',
                        'sort_field',
                        'sort_direction',
                        'copied',
                        'image_small_id',
                        'image_middle_id',
                        'image_big_id',
                        'status',
                    ])
                    ->with([
                        'direction' => function ($query) {
                            $query->select([
                                'directions.id',
                                'directions.metatag_id',
                                'directions.name',
                                'directions.header',
                                'directions.header_template',
                                'directions.weight',
                                'directions.link',
                                'directions.status',
                            ])->where('status', true);
                        },
                    ]);

                if ($this->direction_id) {
                    $query->where('direction_id', $this->direction_id);
                }

                $queryCount = $query->clone();

                $query->orderBy('name', 'ASC');

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => CollectionEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
