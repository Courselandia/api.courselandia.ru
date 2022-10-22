<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

/**
 * Сущность для создания пользователя.
 */
class UserCreate extends UserUpdate
{
    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Выслать приглашение.
     *
     * @var bool
     */
    public bool $invitation = false;
}