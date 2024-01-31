<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Events\Listeners;

use App\Modules\Skill\Models\Skill;

/**
 * Класс обработчик событий для модели навыков.
 */
class SkillListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Skill $skill Модель для таблицы навыков.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Skill $skill): bool
    {
        $skill->deleteRelation($skill->metatag(), $skill->isForceDeleting());
        $skill->courses()->detach();
        $skill->deleteRelation($skill->analyzers(), $skill->isForceDeleting());
        $skill->deleteRelation($skill->articles(), $skill->isForceDeleting());

        return true;
    }
}
