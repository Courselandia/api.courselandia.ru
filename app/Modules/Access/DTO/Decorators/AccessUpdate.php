<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\DTO\Decorators;

use App\Models\DTO;
use App\Modules\User\Entities\User;

/**
 * DTO для декоратора изменения информации о пользователе.
 */
class AccessUpdate extends DTO
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
    public string|null $first_name;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public string|null $second_name;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public string|null $phone;

    /**
     * Сущность пользователя.
     *
     * @var User|null
     */
    public User|null $user;

    /**
     * @param string|int $id ID пользователя.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param User|null $user Сущность пользователя.
     */
    public function __construct(
        string|int $id,
        ?string    $first_name,
        ?string    $second_name,
        ?string    $phone,
        ?User      $user = null,
    )
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->user = $user;
    }
}
