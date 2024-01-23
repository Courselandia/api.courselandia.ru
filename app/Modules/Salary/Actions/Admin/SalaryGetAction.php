<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Models\Salary;
use Cache;
use Util;

/**
 * Класс действия для получения зарплаты.
 */
class SalaryGetAction extends Action
{
    /**
     * ID зарплаты.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID зарплаты.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SalaryEntity
    {
        $cacheKey = Util::getKey('salary', $this->id, 'profession');

        return Cache::tags(['catalog', 'profession', 'salary'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $salary = Salary::where('id', $this->id)
                    ->with('profession')
                    ->first();

                return $salary ? SalaryEntity::from($salary->toArray()) : null;
            }
        );
    }
}
