<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Events\Listeners;

use App\Modules\Employment\Models\Employment;

/**
 * Класс обработчик событий для модели трудоустройства.
 */
class EmploymentListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Employment  $employment  Модель для таблицы трудоустройства.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Employment $employment): bool
    {
        $employment->courses()->detach();

        return true;
    }
}
