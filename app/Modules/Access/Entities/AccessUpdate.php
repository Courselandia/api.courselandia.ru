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
 * Сущность обновления данных пользователя.
 */
class AccessUpdate extends Entity
{
    /**
     * ID пользователя.
     *
     * @var string|int
     */
    public string|int $id;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public ?string $second_name;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone;

    /**
     * Сущность пользователя.
     *
     * @var User|null
     */
    public ?User $user = null;
}