<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Repositories\Salary;
use Cache;

/**
 * Класс действия для обновления статуса зарплат.
 */
class SalaryUpdateStatusAction extends Action
{
    /**
     * Репозиторий зарплат.
     *
     * @var Salary
     */
    private Salary $salary;

    /**
     * ID зарплаты.
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
     * @param  Salary  $salary  Репозиторий зарплат.
     */
    public function __construct(Salary $salary)
    {
        $this->salary = $salary;
    }

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): SalaryEntity
    {
        $action = app(SalaryGetAction::class);
        $action->id = $this->id;
        $salaryEntity = $action->run();

        if ($salaryEntity) {
            $salaryEntity->status = $this->status;
            $this->salary->update($this->id, $salaryEntity);
            Cache::tags(['catalog', 'profession', 'salary'])->flush();

            return $salaryEntity;
        }

        throw new RecordNotExistException(
            trans('salary::actions.admin.salaryUpdateStatusAction.notExistSalary')
        );
    }
}
