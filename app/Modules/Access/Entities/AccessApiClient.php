<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\User\Entities\User;

/**
 * Сущность для хранения API клиента.
 */
class AccessApiClient extends Entity
{
    /**
     * Сущность пользователя.
     *
     * @var User
     */
    public User $user;

    /**
     * Секретный ключ.
     *
     * @var string
     */
    public string $secret;
}