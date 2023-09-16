<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Carbon\Carbon;
use Generator;
use App\Modules\Core\Sitemap\Item;
use App\Modules\Core\Sitemap\Part;

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
