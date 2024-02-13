<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\Salary\Models\Salary;

/**
 * Класс обработчик событий для модели зарплат.
 */
class SalaryListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param Salary $salary Модель для таблицы зарплаты.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(Salary $salary): bool
    {
        $result = $salary->newQuery()
            ->where('profession_id', $salary->profession_id)
            ->where('level', $salary->level)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('salary::events.listeners.salaryListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param Salary $salary Модель для таблицы зарплаты.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(Salary $salary): bool
    {
        $result = $salary->newQuery()
            ->where('id', '!=', $salary->id)
            ->where('profession_id', $salary->profession_id)
            ->where('level', $salary->level)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('salary::events.listeners.salaryListener.existError'));
        }

        return true;
    }
}
