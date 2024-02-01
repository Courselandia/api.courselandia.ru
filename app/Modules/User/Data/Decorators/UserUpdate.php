<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Data\Decorators;

use App\Models\Data;
use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;

/**
 * Данные для декоратора обновления пользователя.
 */
class UserUpdate extends Data
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
    public ?string $first_name = null;

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
     * Статус.
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

    /**
     * @param string|int|null $id ID пользователя.
     * @param string|null $login Логин.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param bool $status Статус.
     * @param bool $verified Статус верификации.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param UploadedFile|null $image Изображение.
     * @param Role|null $role Роль.
     */
    public function __construct(
        string|int|null $id = null,
        ?string         $login = null,
        ?string         $first_name = null,
        ?string         $second_name = null,
        ?string         $phone = null,
        bool            $status = false,
        bool            $verified = false,
        bool            $two_factor = false,
        ?UploadedFile   $image = null,
        ?Role           $role = null,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->status = $status;
        $this->verified = $verified;
        $this->two_factor = $two_factor;
        $this->image = $image;
        $this->role = $role;
    }
}
