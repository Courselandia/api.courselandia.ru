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
use App\Modules\Salary\Repositories\Salary;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления зарплат.
 */
class SalaryUpdateAction extends Action
{
    /**
     * Репозиторий зарплат.
     *
     * @var Salary
     */
    private Salary $salaryRep;

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

            $this->salaryRep->update($this->id, $salaryEntity);
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
