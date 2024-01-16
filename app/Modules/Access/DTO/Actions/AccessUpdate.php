<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\DTO\Actions;

use App\Models\DTO;

/**
 * DTO для действия изменения информации о пользователе.
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
     * @param string|int $id ID пользователя.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     */
    public function __construct(
        string|int $id,
        ?string    $first_name,
        ?string    $second_name,
        ?string    $phone
    )
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
    }
}
