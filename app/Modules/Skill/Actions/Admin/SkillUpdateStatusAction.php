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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Repositories\Skill;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса навыков.
 */
class SkillUpdateStatusAction extends Action
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
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * @return SkillEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): SkillEntity
    {
        $action = app(SkillGetAction::class);
        $action->id = $this->id;
        $skillEntity = $action->run();

        if ($skillEntity) {
            $skillEntity->status = $this->status;
            $this->skill->update($this->id, $skillEntity);
            Cache::tags(['catalog', 'skill'])->flush();

            return $skillEntity;
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
        );
    }
}
