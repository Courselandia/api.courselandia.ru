<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Data\Decorators\UserUpdate as UserUpdateDecoratorData;
use App\Modules\User\Decorators\Admin\User\UserUpdateDecorator;
use App\Modules\User\Entities\User;
use App\Modules\User\Pipes\Admin\User\Create\GetPipe;
use App\Modules\User\Pipes\Admin\User\Create\VerifyPipe;
use App\Modules\User\Pipes\Admin\User\Update\UpdatePipe;
use App\Modules\User\Pipes\Admin\User\Update\ImagePipe;
use App\Modules\User\Pipes\Admin\User\Create\RolePipe;

/**
 * Создание пользователя.
 */
class UserUpdateAction extends Action
{
    /**
     * Данные для декоратора обновления пользователя.
     *
     * @var UserUpdate
     */
    private UserUpdate $data;

    /**
     * @param UserUpdate $data Данные для декоратора обновления пользователя.
     */
    public function __construct(UserUpdate $data)
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
        $decorator = new UserUpdateDecorator(UserUpdateDecoratorData::from($this->data->toArray()));

        return $decorator->setActions([
            UpdatePipe::class,
            VerifyPipe::class,
            ImagePipe::class,
            RolePipe::class,
            GetPipe::class
        ])->run();
    }
}
