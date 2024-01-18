<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Modules\Employment\Data\EmploymentUpdate;
use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;

/**
 * Класс действия для обновления трудоустройства.
 */
class EmploymentUpdateAction extends Action
{
    /**
     * Данные для обновления трудоустройства.
     *
     * @var EmploymentUpdate
     */
    private EmploymentUpdate $data;

    /**
     * @param EmploymentUpdate $data Данные для обновления трудоустройства.
     */
    public function __construct(EmploymentUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return EmploymentEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): EmploymentEntity
    {
        $action = new EmploymentGetAction($this->data->id);
        $employmentEntity = $action->run();

        if ($employmentEntity) {
            $employmentEntity->id = $this->data->id;
            $employmentEntity->name = Typography::process($this->data->name, true);
            $employmentEntity->text = Typography::process($this->data->text);
            $employmentEntity->status = $this->data->status;

            Employment::find($this->data->id)->update($employmentEntity->toArray());
            Cache::tags(['catalog', 'employment'])->flush();

            $action = new EmploymentGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('employment::actions.admin.employmentUpdateAction.notExistEmployment')
        );
    }
}
