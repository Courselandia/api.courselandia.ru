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
use App\Modules\Salary\Repositories\Salary;
use Cache;

/**
 * Класс действия для удаления зарплаты.
 */
class SalaryDestroyAction extends Action
{
    /**
     * Репозиторий зарплат.
     *
     * @var Salary
     */
    private Salary $salary;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

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
     * @return bool Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->salary->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'profession', 'salary'])->flush();
        }

        return true;
    }
}
