<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Actions\Admin;

use App\Models\Action;
use App\Modules\Salary\Models\Salary;
use Cache;

/**
 * Класс действия для удаления зарплаты.
 */
class SalaryDestroyAction extends Action
{
    /**
     * Массив ID зарплат.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID зарплат.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Salary::destroy($this->ids);
            Cache::tags(['catalog', 'profession', 'salary'])->flush();
        }

        return true;
    }
}
