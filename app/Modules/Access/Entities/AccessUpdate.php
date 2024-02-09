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
    public string|null $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public string|null $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public string|null $phone = null;

    /**
     * Сущность пользователя.
     *
     * @var User|null
     */
    public User|null $user = null;

    /**
     * @param string|int $id ID пользователя.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param User|null $user Сущность пользователя.
     */
    public function __construct(
        string|int $id,
        ?string $first_name = null,
        ?string $second_name = null,
        ?string $phone = null,
        ?User $user = null
    )
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->user = $user;
    }
}
