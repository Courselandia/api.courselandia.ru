<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Filters;

use Config;
use Carbon\Carbon;
use App\Modules\Task\Enums\Status;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы заданий.
 */
class TaskFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'user' => [
            'user-id' => 'userId',
        ],
    ];

    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return TaskFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('tasks.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return TaskFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('tasks.name', $query);
    }

    /**
     * Поиск по причине ошибки.
     *
     * @param string $query Строка поиска.
     *
     * @return TaskFilter Правила поиска.
     */
    public function reason(string $query): self
    {
        return $this->whereLike('tasks.reason', $query);
    }

    /**
     * Поиск по пользователям.
     *
     * @param array|int|string $userIds ID's пользователей.
     *
     * @return TaskFilter Правила поиска.
     */
    public function userId(array|int|string $userIds): self
    {
        return $this->whereIn('tasks.user_id', is_array($userIds) ? $userIds : [$userIds]);
    }

    /**
     * Поиск по статусу.
     *
     * @param array|Status|string $statuses Статусы.
     *
     * @return TaskFilter Правила поиска.
     */
    public function status(array|Status|string $statuses): TaskFilter
    {
        return $this->where('tasks.status', is_array($statuses) ? $statuses : [$statuses]);
    }

    /**
     * Поиск по дате создания.
     *
     * @param array $dates Даты от и до.
     *
     * @return TaskFilter Правила поиска.
     */
    public function createdAt(array $dates): TaskFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('tasks.created_at', $dates);
    }

    /**
     * Поиск по дате запуска.
     *
     * @param array $dates Даты от и до.
     *
     * @return TaskFilter Правила поиска.
     */
    public function launchedAt(array $dates): TaskFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('tasks.launched_at', $dates);
    }

    /**
     * Поиск по дате завершения.
     *
     * @param array $dates Даты от и до.
     *
     * @return TaskFilter Правила поиска.
     */
    public function finishedAt(array $dates): TaskFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('tasks.finished_at', $dates);
    }
}
