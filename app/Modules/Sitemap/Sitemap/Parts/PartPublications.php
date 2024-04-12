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
use App\Modules\Publication\Models\Publication;
use App\Modules\Sitemap\Sitemap\Item;

/**
 * Генератор для списка публикаций.
 */
class PartPublications extends PartDirection
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
        $publication = Publication::active()
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $item = new Item();
        $item->path = '/blog';
        $item->priority = 1;
        $item->lastmod = $publication ? $publication->published_at : Carbon::now();

        yield $item;
    }
}
