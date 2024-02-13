<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Data\Actions;

use App\Models\Data;
use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;

/**
 * Данные для действия создание пользователя.
 */
class UserCreate extends Data
{
    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

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
     * Статус пользователя.
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
     * Выслать приглашение.
     *
     * @var bool
     */
    public bool $invitation = false;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * @param string|null $login Логин.
     * @param string|null $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param bool $verified Статус верификации.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param bool $status Статус пользователя.
     * @param bool $invitation Выслать приглашение.
     * @param UploadedFile|null $image Изображение.
     * @param Role|null $role Роль.
     */
    public function __construct(
        ?string       $login = null,
        ?string       $password = null,
        ?string       $first_name = null,
        ?string       $second_name = null,
        ?string       $phone = null,
        bool          $verified = false,
        bool          $two_factor = false,
        bool          $status = false,
        bool          $invitation = false,
        ?UploadedFile $image = null,
        ?Role         $role = null
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->verified = $verified;
        $this->two_factor = $two_factor;
        $this->status = $status;
        $this->invitation = $invitation;
        $this->image = $image;
        $this->role = $role;
    }
}
