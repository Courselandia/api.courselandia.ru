<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы пользователей.
 */
class UserFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'role' => [
            'role-name'  => 'roleName',
        ]
    ];

    /**
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return UserFilter Правила поиска.
     */
    public function id(int $id): UserFilter
    {
        return $this->where('users.id', $id);
    }

    /**
     * Поиск по логину.
     *
     * @param string $query Строка поиска.
     *
     * @return UserFilter Правила поиска.
     */
    public function login(string $query): UserFilter
    {
        return $this->whereLike('users.login', $query);
    }

    /**
     * Поиск по имени.
     *
     * @param string $query Строка поиска.
     *
     * @return UserFilter Правила поиска.
     */
    public function firstName(string $query): UserFilter
    {
        return $this->whereLike('users.first_name', $query);
    }

    /**
     * Поиск по фамилии.
     *
     * @param string $query Строка поиска.
     *
     * @return UserFilter Правила поиска.
     */
    public function secondName(string $query): UserFilter
    {
        return $this->whereLike('users.second_name', $query);
    }

    /**
     * Поиск по телефону.
     *
     * @param string $query Строка поиска.
     *
     * @return UserFilter Правила поиска.
     */
    public function phone(string $query): UserFilter
    {
        return $this->whereLike('users.phone', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return UserFilter Правила поиска.
     */
    public function status(bool $status): UserFilter
    {
        return $this->where('users.status', $status);
    }

    /**
     * Поиск по дате создания.
     *
     * @param array $dates Даты от и до.
     *
     * @return UserFilter Правила поиска.
     */
    public function createdAt(array $dates): UserFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('users.created_at', $dates);
    }

    /**
     * Поиск по названию роли.
     *
     * @param string[]|string $roles Название ролей.
     *
     * @return UserFilter Правила поиска.
     */
    public function roleName(array|string $roles): UserFilter
    {
        return $this->related('role', function($query) use ($roles) {
            return $query->whereIn('name', is_array($roles) ? $roles : [$roles]);
        });
    }
}
