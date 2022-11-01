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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Salary\Entities\Salary as SalaryEntity;
use App\Modules\Salary\Repositories\Salary;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения зарплаты.
 */
class SalaryGetAction extends Action
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
     * @return SalaryEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?SalaryEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'profession',
            ]);

        $cacheKey = Util::getKey('salary', $query);

        return Cache::tags(['catalog', 'profession', 'salary'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->salary->get($query);
            }
        );
    }
}
