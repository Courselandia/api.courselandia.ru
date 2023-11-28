<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use App\Modules\Sitemap\Sitemap\Item;
use Generator;

/**
 * Генератор для общей страницы курсов.
 */
class PartCourses extends PartDirection
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
        $item = new Item();
        $item->path = '/courses';
        $item->priority = 0.7;
        $item->lastmod = $this->getLastmod();

        yield $item;
    }
}
