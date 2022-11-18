<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use Cache;

/**
 * Класс действия для обновления статуса профессий.
 */
class ProfessionUpdateStatusAction extends Action
{
    /**
     * ID профессии.
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
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): ProfessionEntity
    {
        $action = app(ProfessionGetAction::class);
        $action->id = $this->id;
        $professionEntity = $action->run();

        if ($professionEntity) {
            $professionEntity->status = $this->status;

            Profession::find($this->id)->update($professionEntity->toArray());
            Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();

            return $professionEntity;
        }

        throw new RecordNotExistException(
            trans('profession::actions.admin.professionUpdateStatusAction.notExistProfession')
        );
    }
}
