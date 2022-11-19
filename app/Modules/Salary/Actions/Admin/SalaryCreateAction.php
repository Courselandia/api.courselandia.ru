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
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Enums\Level;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для создания зарплаты.
 */
class SalaryCreateAction extends Action
{
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
     * @throws ParameterInvalidException
     */
    public function run(): SalaryEntity
    {
        $salaryEntity = new SalaryEntity();
        $salaryEntity->level = $this->level;
        $salaryEntity->salary = $this->salary;
        $salaryEntity->profession_id = $this->profession_id;
        $salaryEntity->status = $this->status;

        $salary = Salary::create($salaryEntity->toArray());
        Cache::tags(['catalog', 'profession', 'salary'])->flush();

        $action = app(SalaryGetAction::class);
        $action->id = $salary->id;

        return $action->run();
    }
}
