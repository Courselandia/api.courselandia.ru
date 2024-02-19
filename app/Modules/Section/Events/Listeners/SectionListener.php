<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Events\Listeners;

use App\Modules\Section\Models\Section;

/**
 * Класс обработчик событий для модели навыков.
 */
class SectionListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Section $section Модель для таблицы навыков.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Section $section): bool
    {
        $section->deleteRelation($section->metatag(), $section->isForceDeleting());
        $section->deleteRelation($section->items(), $section->isForceDeleting());

        return true;
    }
}
