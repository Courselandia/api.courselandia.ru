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
use App\Modules\Salary\Enums\Level;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для обновления зарплат.
 */
class SalaryUpdateAction extends Action
{
    /**
     * ID зарплаты.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID профессии.
     *
     * @var int|null
     */
    public ?int $profession_id = null;

    /**
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;

    /**
     * Зарплата.
     *
     * @var int|null
     */
    public ?int $salary = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): SalaryEntity
    {
        $action = app(SalaryGetAction::class);
        $action->id = $this->id;
        $salaryEntity = $action->run();

        if ($salaryEntity) {
            $salaryEntity->id = $this->id;
            $salaryEntity->level = $this->level;
            $salaryEntity->salary = $this->salary;
            $salaryEntity->profession_id = $this->profession_id;
            $salaryEntity->status = $this->status;

            Salary::find($this->id)->update($salaryEntity->toArray());
            Cache::tags(['catalog', 'profession', 'salary'])->flush();

            $action = app(SalaryGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('salary::actions.admin.salaryUpdateAction.notExistSalary')
        );
    }
}
