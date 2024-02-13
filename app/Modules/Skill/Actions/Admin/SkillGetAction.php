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
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID навыка.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SkillEntity
    {
        $cacheKey = Util::getKey('skill', $this->id);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $skill = Skill::where('id', $this->id)
                    ->with([
                        'metatag',
                        'analyzers',
                    ])
                    ->first();

                return $skill ? SkillEntity::from($skill->toArray()) : null;
            }
        );
    }
}
