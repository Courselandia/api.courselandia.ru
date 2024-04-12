<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use Generator;
use Carbon\Carbon;
use App\Modules\Collection\Models\Collection;
use App\Modules\Sitemap\Sitemap\Item;

/**
 * Генератор для списка коллекций.
 */
class PartCollections extends PartDirection
{
    /**
     * Вернет количество генерируемых элементов.
     *
     * @return int Количество элементов.
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * Генерация элемента.
     *
     * @return Generator<Item> Генерируемый элемент.
     */
    public function generate(): Generator
    {
        $collection = Collection::active()
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $item = new Item();
        $item->path = '/collections';
        $item->priority = 0.7;
        $item->lastmod = $collection ? Carbon::parse($collection->updated_at) : Carbon::now();

        yield $item;
    }
}
