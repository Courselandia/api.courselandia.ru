<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class UserRoleFilter extends ModelFilter
{
    /**
     * Поиск по роли.
     *
     * @param string[] $roles Название ролей.
     *
     * @return self Правила поиска.
     */
    public function name(array $roles): self
    {
        return $this->whereIn('user_roles.name', $roles);
    }
}
