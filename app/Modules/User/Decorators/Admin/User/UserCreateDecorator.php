<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Decorators\Admin\User;

use App\Models\Decorator;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Entities\User;
use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для создания пользователя.
 */
class UserCreateDecorator extends Decorator
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
    public string|null $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public string|null $password = null;

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
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

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
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return User Вернет сущность пользователя.
     */
    public function run(): User
    {
        $userCreate = new UserCreate();

        $userCreate->login = $this->login;
        $userCreate->password = $this->password;
        $userCreate->first_name = $this->first_name;
        $userCreate->second_name = $this->second_name;
        $userCreate->phone = $this->phone;
        $userCreate->verified = $this->verified;
        $userCreate->two_factor = $this->two_factor;
        $userCreate->status = $this->status;
        $userCreate->image = $this->image;
        $userCreate->role = $this->role;
        $userCreate->invitation = $this->invitation;

        return app(Pipeline::class)
            ->send($userCreate)
            ->through($this->getActions())
            ->thenReturn();
    }
}
