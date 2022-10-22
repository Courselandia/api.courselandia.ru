<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Decorators\Admin\User;

use App\Models\Decorator;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserUpdate;
use App\Modules\User\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для обновления пользователя.
 */
class UserUpdateDecorator extends Decorator
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
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return User Вернет сущность пользователя.
     */
    public function run(): User
    {
        $userUpdate = new UserUpdate();

        $userUpdate->id = $this->id;
        $userUpdate->login = $this->login;
        $userUpdate->first_name = $this->first_name;
        $userUpdate->second_name = $this->second_name;
        $userUpdate->phone = $this->phone;
        $userUpdate->verified = $this->verified;
        $userUpdate->two_factor = $this->two_factor;
        $userUpdate->image = $this->image;
        $userUpdate->role = $this->role;
        $userUpdate->status = $this->status;

        return app(Pipeline::class)
            ->send($userUpdate)
            ->through($this->getActions())
            ->thenReturn();
    }
}
