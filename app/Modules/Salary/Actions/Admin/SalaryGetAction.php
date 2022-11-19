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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return SalaryEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
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

                return $salary ? new SalaryEntity($salary->toArray()) : null;
            }
        );
    }
}
