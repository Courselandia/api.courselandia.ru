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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Repositories\Skill;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения навыка.
 */
class SkillGetAction extends Action
{
    /**
     * Репозиторий навыков.
     *
     * @var Skill
     */
    private Skill $skill;

    /**
     * ID навыка.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Skill  $skill  Репозиторий навыков.
     */
    public function __construct(Skill $skill)
    {
        $this->skill = $skill;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?SkillEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('skill', $query);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->skill->get($query);
            }
        );
    }
}
