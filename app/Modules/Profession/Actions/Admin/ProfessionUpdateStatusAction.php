<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
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
     * @var int|string
     */
    public int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    public bool $status;

    /**
     * @param int|string $id ID профессии.
     * @param bool $status Статус.
     */
    public function __construct(int|string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ProfessionEntity
    {
        $action = new ProfessionGetAction($this->id);
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
