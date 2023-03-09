<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;

/**
 * Класс действия для получения категории.
 */
class SkillGetAction extends Action
{
    /**
     * ID категории.
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
        $cacheKey = Util::getKey('skill', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Skill::with([
                    'metatag',
                ])->find($this->id);

                if ($result) {
                    $item = $result->toArray();
                    $entity = new SkillEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
