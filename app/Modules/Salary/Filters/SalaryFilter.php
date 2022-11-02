<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Filters;

use App\Modules\Salary\Enums\Level;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class SalaryFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'profession' => [
            'profession-name'  => 'professionName',
        ]
    ];

    /**
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return SalaryFilter Правила поиска.
     */
    public function id(int $id): SalaryFilter
    {
        return $this->where('salaries.id', $id);
    }

    /**
     * Поиск по профессии.
     *
     * @param array|int $professionIds ID's профессий.
     *
     * @return SalaryFilter Правила поиска.
     */
    public function professionName(array|int $professionIds): SalaryFilter
    {
        return $this->related('profession', function($query) use ($professionIds) {
            return $query->whereIn('professions.id', is_array($professionIds) ? $professionIds : [$professionIds]);
        });
    }

    /**
     * Поиск по уровню.
     *
     * @param Level[]|Level|string[]|string $levels Уровни.
     *
     * @return SalaryFilter Правила поиска.
     */
    public function level(array|Level|string $levels): SalaryFilter
    {
        return $this->whereIn('salaries.level', is_array($levels) ? $levels : [$levels]);
    }

    /**
     * Поиск по уровню.
     *
     * @param int $salary Зарплата.
     *
     * @return SalaryFilter Правила поиска.
     */
    public function salary(int $salary): SalaryFilter
    {
        return $this->where('salaries.salary', $salary);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return SalaryFilter Правила поиска.
     */
    public function status(bool $status): SalaryFilter
    {
        return $this->where('salaries.status', $status);
    }
}
