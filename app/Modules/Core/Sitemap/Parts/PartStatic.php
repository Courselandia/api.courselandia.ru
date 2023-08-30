<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Generator;
use App\Modules\Core\Sitemap\Item;

/**
 * Генератор для статических файлов.
 */
class PartStatic extends PartDirection
{
    private array $paths = [
        'agreement',
        'privacy-policy',
        'courses',
        'reviews'
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
            $item->priority = 0.5;

            yield $item;
        }
    }
}
