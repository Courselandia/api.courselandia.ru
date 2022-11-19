<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Skill\Models\Skill;
use Cache;

/**
 * Класс действия для удаления навыка.
 */
class SkillDestroyAction extends Action
{
    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            Skill::destroy($this->ids);
            Cache::tags(['catalog', 'skill'])->flush();
        }

        return true;
    }
}
