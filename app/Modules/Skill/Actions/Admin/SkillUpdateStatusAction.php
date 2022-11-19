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
use App\Modules\Skill\Models\Skill;
use Cache;

/**
 * Класс действия для обновления статуса навыков.
 */
class SkillUpdateStatusAction extends Action
{
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
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): SkillEntity
    {
        $action = app(SkillGetAction::class);
        $action->id = $this->id;
        $skillEntity = $action->run();

        if ($skillEntity) {
            $skillEntity->status = $this->status;
            Skill::find($this->id)->update($skillEntity->toArray());
            Cache::tags(['catalog', 'skill'])->flush();

            return $skillEntity;
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
        );
    }
}
