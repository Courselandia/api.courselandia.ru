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
use App\Modules\User\Data\Actions\UserCreate;
use App\Modules\User\Data\Decorators\UserCreate as UserCreateDecoratorData;

/**
 * Создание пользователя.
 */
class UserCreateAction extends Action
{
    /**
     * Данные для действия создание пользователя.
     *
     * @var UserCreate
     */
    private UserCreate $data;

    /**
     * @param UserCreate $data Данные для действия создание пользователя.
     */
    public function __construct(UserCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return User Вернет сущность пользователя.
     */
    public function run(): User
    {
        $decorator = new UserCreateDecorator(UserCreateDecoratorData::from($this->data->toArray()));

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
