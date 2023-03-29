<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap;

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
    public string $changefreq = 'day';

    /**
     * Приоритетность.
     *
     * @var int
     */
    public int $priority = 1;
}
