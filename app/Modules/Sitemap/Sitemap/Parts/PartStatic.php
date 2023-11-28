<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use App\Modules\Sitemap\Sitemap\Item;
use App\Modules\Sitemap\Sitemap\Part;
use Carbon\Carbon;
use Generator;

/**
 * Генератор для статических файлов.
 */
class PartStatic extends Part
{
    private array $paths = [
        '/agreement',
        '/privacy-policy',
    ];

    /**
     * Вернет количество генерируемых элементов.
     *
     * @return int Количество элементов.
     */
    public function count(): int
    {
        return count($this->paths);
    }

    /**
     * Генерация элемента.
     *
     * @return Generator<Item> Генерируемый элемент.
     */
    public function generate(): Generator
    {
        foreach ($this->paths as $path) {
            $item = new Item();
            $item->path = $path;
            $item->priority = 0.1;
            $item->lastmod = Carbon::createFromFormat('Y-m-d', '2013-06-01');

            yield $item;
        }
    }
}
