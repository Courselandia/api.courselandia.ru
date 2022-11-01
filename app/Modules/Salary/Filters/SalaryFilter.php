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
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return SalaryFilter Правила валидации.
     */
    public function id(int $id): SalaryFilter
    {
        return $this->where('salaries.id', $id);
    }

    /**
     * Поиск по профессии.
     *
     * @param string $query Строка поиска.
     *
     * @return SalaryFilter Правила валидации.
     */
    public function professionId(string $query): SalaryFilter
    {
        return $this->whereLike('salaries.profession_id', $query);
    }

    /**
     * Поиск по уровню.
     *
     * @param Level $query Строка поиска.
     *
     * @return SalaryFilter Правила валидации.
     */
    public function level(Level $query): SalaryFilter
    {
        return $this->whereLike('salaries.level', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return SalaryFilter Правила валидации.
     */
    public function status(bool $status): SalaryFilter
    {
        return $this->where('salaries.status', $status);
    }
}
