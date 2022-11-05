<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Events\Listeners;

use App\Modules\Profession\Models\Profession;

/**
 * Класс обработчик событий для модели профессий.
 */
class ProfessionListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Profession  $profession  Модель для таблицы профессий.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Profession $profession): bool
    {
        $profession->deleteRelation($profession->metatag(), $profession->isForceDeleting());
        $profession->deleteRelation($profession->salaries(), $profession->isForceDeleting());
        $profession->categories()->detach();
        $profession->courses()->detach();

        return true;
    }
}
