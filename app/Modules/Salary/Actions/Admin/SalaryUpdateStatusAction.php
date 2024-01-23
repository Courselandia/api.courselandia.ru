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
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для обновления статуса зарплат.
 */
class SalaryUpdateStatusAction extends Action
{
    /**
     * ID зарплаты.
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
     * @param int|string $id ID зарплаты.
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
     * @return SalaryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): SalaryEntity
    {
        $action = new SalaryGetAction($this->id);
        $salaryEntity = $action->run();

        if ($salaryEntity) {
            $salaryEntity->status = $this->status;
            Salary::find($this->id)->update($salaryEntity->toArray());
            Cache::tags(['catalog', 'profession', 'salary'])->flush();

            return $salaryEntity;
        }

        throw new RecordNotExistException(
            trans('salary::actions.admin.salaryUpdateStatusAction.notExistSalary')
        );
    }
}
