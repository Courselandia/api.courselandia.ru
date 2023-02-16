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
     * @return EmploymentEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): EmploymentEntity
    {
        $action = app(EmploymentGetAction::class);
        $action->id = $this->id;
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
