<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Site;

use Cache;
use Config;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;
use App\Modules\Salary\Enums\Level;

/**
 * Класс действия для получения раздела.
 */
class SectionLinkAction extends Action
{
    /**
     * Элементы.
     *
     * @var string[]
     */
    private array $items;

    /**
     * Уровень.
     *
     * @var ?Level
     */
    private ?Level $level;

    /**
     * Признак бесплатности.
     *
     * @var bool
     */
    private bool $free;

    /**
     * @param array $items Элементы.
     * @param Level|null $level Уровень.
     * @param bool $free Признак бесплатности.
     */
    public function __construct(array $items, ?Level $level = null, bool $free = false)
    {
        $this->items = $items;
        $this->level = $level;
        $this->free = $free;
    }

    /**
     * Метод запуска логики.
     *
     * @return SectionEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SectionEntity
    {
        $cacheKey = Util::getKey(
            'section',
            'site',
            'link',
            $this->items,
            $this->level,
            $this->free,
        );

        return Cache::tags(['catalog', 'section'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Section::active()
                    ->with([
                        'metatag',
                        'items.itemable',
                    ]);

                if ($this->level) {
                    $query->where('level', $this->level->value);
                }

                if ($this->free) {
                    $query->where('free', true);
                }

                $weight = 0;

                $items = Config::get('section.items');

                foreach ($this->items as $item) {
                    $query->whereHas('items', static function ($query) use ($item, $weight, $items) {
                        $query->where('itemable_type', $items[$item['type']])
                            ->where('weight', $weight)
                            ->whereHas('itemable', static function ($query) use ($item) {
                                $query->where('link', $item['link']);
                            });
                    });

                    $weight++;
                }

                $section = $query->first();

                if ($section) {
                    $items = Config::get('section.items');
                    $entity = SectionEntity::from($section->toArray());

                    foreach ($entity->items as $item) {
                        $item->type = array_search($item->itemable_type, $items);
                    }

                    return $entity;
                }

                return null;
            }
        );
    }
}
