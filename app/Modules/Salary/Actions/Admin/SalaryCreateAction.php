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
use App\Modules\Salary\Repositories\Salary;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания зарплаты.
 */
class SalaryCreateAction extends Action
{
    /**
     * Репозиторий зарплат.
     *
     * @var Salary
     */
    private Salary $salaryRep;

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
     * Конструктор.
     *
     * @param  Salary  $salary  Репозиторий зарплат.
     */
    public function __construct(Salary $salary)
    {
        $this->salaryRep = $salary;
    }

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): SalaryEntity
    {
        $salaryEntity = new SalaryEntity();
        $salaryEntity->level = $this->level;
        $salaryEntity->salary = $this->salary;
        $salaryEntity->profession_id = $this->profession_id;
        $salaryEntity->status = $this->status;

        $id = $this->salaryRep->create($salaryEntity);
        Cache::tags(['catalog', 'profession', 'salary'])->flush();

        $action = app(SalaryGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
