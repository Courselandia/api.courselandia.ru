<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Data\Decorators;

use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;

/**
 * Данные для декоратора создания пользователя.
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

    /**
     * @param string|null $login Логин.
     * @param string|null $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param bool $status Статус.
     * @param bool $verified Статус верификации.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param bool $invitation Выслать приглашение.
     * @param UploadedFile|null $image Изображение.
     * @param Role|null $role Роль.
     */
    public function __construct(
        ?string         $login = null,
        ?string         $password = null,
        ?string         $first_name = null,
        ?string         $second_name = null,
        ?string         $phone = null,
        bool            $status = false,
        bool            $verified = false,
        bool            $two_factor = false,
        bool            $invitation = false,
        ?UploadedFile   $image = null,
        ?Role           $role = null,
    )
    {
        $this->password = $password;
        $this->invitation = $invitation;

        parent::__construct(
            null,
            $login,
            $first_name,
            $second_name,
            $phone,
            $status,
            $verified,
            $two_factor,
            $image,
            $role,
        );
    }
}
