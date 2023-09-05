<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Generator;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Core\Sitemap\Item;

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
     * @throws ParameterInvalidException
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
