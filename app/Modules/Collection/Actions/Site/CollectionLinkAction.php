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
use App\Modules\Course\Enums\Status;

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

        Cache::flush();

        return Cache::tags(['catalog', 'collection'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $collection = Collection::active()
                ->with([
                    'direction',
                    'metatag',
                    'courses' => function ($query) {
                        $query->select([
                            'courses.id',
                            'courses.school_id',
                            'courses.image_middle_id',
                            'courses.name',
                            'courses.header',
                            'courses.header_template',
                            'courses.text',
                            'courses.link',
                            'courses.url',
                            'courses.language',
                            'courses.rating',
                            'courses.price',
                            'courses.price_old',
                            'courses.price_recurrent',
                            'courses.currency',
                            'courses.online',
                            'courses.employment',
                            'courses.duration',
                            'courses.duration_rate',
                            'courses.duration_unit',
                            'courses.lessons_amount',
                            'courses.modules_amount',
                            'courses.program',
                            'courses.status',
                            'courses.updated_at',
                        ])
                        ->where('status', Status::ACTIVE->value);
                    },
                    'courses.school' => function ($query) {
                        $query->select([
                            'schools.id',
                            'schools.name',
                            'schools.link',
                            'schools.image_logo_id',
                        ])->where('status', true);
                    },
                    'courses.learns',
                    'courses.tools' => function ($query) {
                        $query->where('status', true);
                    },
                    'courses.teachers' => function ($query) {
                        $query->where('status', true);
                    },
                ])
                ->where('link', $this->link)
                ->first();

                return $collection ? CollectionEntity::from($collection->toArray()) : null;
            }
        );
    }
}
