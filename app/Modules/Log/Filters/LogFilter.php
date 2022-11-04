<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы логов.
 */
class LogFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return LogFilter Правила поиска.
     */
    public function id(int|string $id): LogFilter
    {
        return $this->where($this->getKeyName(), $id);
    }

    /**
     * Поиск по дате.
     *
     * @param array $dates Даты.
     *
     * @return LogFilter Правила поиска.
     */
    public function createdAt(array $dates): LogFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone'))->unix(),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone'))->unix(),
        ];

        return $this->whereBetween('unix_time', $dates);
    }

    /**
     * Поиск сообщению.
     *
     * @param string $query Строка поиска.
     *
     * @return LogFilter Правила поиска.
     */
    public function message(string $query): LogFilter
    {
        return $this->whereLike('message', $query);
    }

    /**
     * Поиск по уровню ошибки.
     *
     * @param string $query Строка поиска.
     *
     * @return LogFilter Правила поиска.
     */
    public function levelName(string $query): LogFilter
    {
        return $this->whereLike('level_name', $query);
    }
}
