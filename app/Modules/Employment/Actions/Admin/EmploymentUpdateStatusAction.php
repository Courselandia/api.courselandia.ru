<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;
use Cache;

/**
 * Класс действия для обновления статуса трудоустройства.
 */
class EmploymentUpdateStatusAction extends Action
{
    /**
     * ID трудоустройства.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param int|string $id ID трудоустройства.
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
     * @return EmploymentEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): EmploymentEntity
    {
        $action = new EmploymentGetAction($this->id);
        $employmentEntity = $action->run();

        if ($employmentEntity) {
            $employmentEntity->status = $this->status;
            Employment::find($this->id)->update($employmentEntity->toArray());
            Cache::tags(['catalog', 'employment'])->flush();

            return $employmentEntity;
        }

        throw new RecordNotExistException(
            trans('employment::actions.admin.employmentUpdateStatusAction.notExistEmployment')
        );
    }
}
