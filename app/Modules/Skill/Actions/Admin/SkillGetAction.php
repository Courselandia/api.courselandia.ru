<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;
use Cache;
use Util;

/**
 * Класс действия для получения навыка.
 */
class SkillGetAction extends Action
{
    /**
     * ID навыка.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?SkillEntity
    {
        $cacheKey = Util::getKey('skill', $this->id);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $skill = Skill::where('id', $this->id)
                    ->with('metatag')
                    ->first();

                return $skill ? new SkillEntity($skill->toArray()) : null;
            }
        );
    }
}
