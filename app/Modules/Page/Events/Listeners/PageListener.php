<?php
/**
 * Модуль Страницы.
 * Этот модуль содержит все классы для работы со списком страниц.
 *
 * @package App\Modules\Page
 */

namespace App\Modules\Page\Events\Listeners;

use App\Modules\Page\Models\Page;

/**
 * Класс обработчик событий для модели страницы.
 */
class PageListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Page $page Модель для таблицы страниц.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Page $page): bool
    {
        $page->deleteRelation($page->crawl(), $page->isForceDeleting());

        return true;
    }
}
