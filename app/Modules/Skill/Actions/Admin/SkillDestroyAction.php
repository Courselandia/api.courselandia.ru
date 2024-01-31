<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Modules\Skill\Models\Skill;
use Cache;

/**
 * Класс действия для удаления навыка.
 */
class SkillDestroyAction extends Action
{
    /**
     * Массив ID навыков.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID навыков.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
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
