<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Modules\User\Decorators\Admin\User\UserCreateDecorator;
use App\Modules\User\Entities\User;
use App\Modules\User\Pipes\Admin\User\Create\CreatePipe;
use App\Modules\User\Pipes\Admin\User\Create\GetPipe;
use App\Modules\User\Pipes\Admin\User\Create\ImagePipe;
use App\Modules\User\Pipes\Admin\User\Create\RolePipe;
use App\Modules\User\Pipes\Admin\User\Create\InvitationPipe;
use App\Modules\User\Pipes\Admin\User\Create\VerifyPipe;
use Illuminate\Http\UploadedFile;
use App\Modules\User\Enums\Role;

/**
 * Создание пользователя.
 */
class UserCreateAction extends Action
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
     * Метод запуска логики.
     *
     * @return User Вернет сущность пользователя.
     */
    public function run(): User
    {
        $decorator = app(UserCreateDecorator::class);

        $decorator->login = $this->login;
        $decorator->password = $this->password;
        $decorator->first_name = $this->first_name;
        $decorator->second_name = $this->second_name;
        $decorator->phone = $this->phone;
        $decorator->verified = $this->verified;
        $decorator->status = $this->status;
        $decorator->two_factor = $this->two_factor;
        $decorator->image = $this->image;
        $decorator->role = $this->role;
        $decorator->invitation = $this->invitation;

        return $decorator->setActions([
            CreatePipe::class,
            VerifyPipe::class,
            ImagePipe::class,
            RolePipe::class,
            InvitationPipe::class,
            GetPipe::class
        ])->run();
    }
}
