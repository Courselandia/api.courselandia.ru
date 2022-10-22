<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\Entity;
use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;

/**
 * Сущность для обновления пользователя.
 */
class UserUpdate extends Entity
{
    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

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
    public ?string $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Телефон.
     *
     * @var bool
     */
    public bool $status = false;

    /**
     * Роль.
     *
     * @var Role|null
     */
    public ?Role $role = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified = false;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor = false;
}
