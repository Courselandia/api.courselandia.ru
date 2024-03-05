<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use App\Modules\Section\Helpers\UrlSection;
use Cache;
use Util;
use Config;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;

/**
 * Класс действия для получения навыка.
 */
class SectionGetAction extends Action
{
    /**
     * ID раздела.
     *
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID раздела.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return SectionEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SectionEntity
    {
        $cacheKey = Util::getKey('section', $this->id);

        return Cache::tags(['catalog', 'section'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $section = Section::where('id', $this->id)
                    ->with([
                        'metatag',
                        'items.itemable',
                    ])
                    ->first();

                if ($section) {
                    $items = Config::get('section.items');
                    $entity = SectionEntity::from($section->toArray());

                    foreach ($entity->items as $item) {
                        $item->type = array_search($item->itemable_type, $items);
                    }

                    $entity->url = UrlSection::get($entity);

                    return $entity;
                }

                return null;
            }
        );
    }
}
