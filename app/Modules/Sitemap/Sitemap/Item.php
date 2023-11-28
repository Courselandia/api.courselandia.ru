<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap;

use Carbon\Carbon;

/**
 * Класс структуры генерируемого элемента.
 */
class Item
{
    /**
     * Путь к странице.
     *
     * @var string
     */
    public string $path;

    /**
     * Как часто обновлять страницу.
     *
     * @var string
     */
    public string $changefreq = 'weekly';

    /**
     * Приоритетность.
     *
     * @var float
     */
    public float $priority = 1;

    /**
     * Дата последней модификации страницы.
     *
     * @var ?Carbon
     */
    public ?Carbon $lastmod= null;
}
