<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Actions\Admin;

use App\Models\Action;
use App\Modules\Salary\Data\SalaryCreate;
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для создания зарплаты.
 */
class SalaryCreateAction extends Action
{
    /**
     * Данные для создания зарплаты.
     *
     * @var SalaryCreate
     */
    private SalaryCreate $data;

    /**
     * @param SalaryCreate $data Данные для создания зарплаты.
     */
    public function __construct(SalaryCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity Вернет результаты исполнения.
     */
    public function run(): SalaryEntity
    {
        $salaryEntity = SalaryEntity::from($this->data->toArray());

        $salary = Salary::create($salaryEntity->toArray());
        Cache::tags(['catalog', 'salary'])->flush();

        $action = new SalaryGetAction($salary->id);

        return $action->run();
    }
}
