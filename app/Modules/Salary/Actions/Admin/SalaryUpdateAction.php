<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Salary\Data\SalaryUpdate;
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для обновления зарплат.
 */
class SalaryUpdateAction extends Action
{
    /**
     * Данные для обновления зарплаты.
     *
     * @var SalaryUpdate
     */
    private SalaryUpdate $data;

    /**
     * @param SalaryUpdate $data Данные для обновления зарплаты.
     */
    public function __construct(SalaryUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): SalaryEntity
    {
        $action = new SalaryGetAction($this->data->id);
        $salaryEntity = $action->run();

        if ($salaryEntity) {
            $salaryEntity = SalaryEntity::from([
                ...$salaryEntity->toArray(),
                ...$this->data->toArray(),
            ]);

            Salary::find($this->data->id)->update($salaryEntity->toArray());
            Cache::tags(['catalog', 'profession', 'salary'])->flush();

            $action = new SalaryGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('salary::actions.admin.salaryUpdateAction.notExistSalary')
        );
    }
}
