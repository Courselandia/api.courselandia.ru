<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\Profile;

use App\Models\Action;
use App\Modules\User\Data\Actions\UserProfileUpdate;
use App\Modules\User\Decorators\Admin\User\UserUpdateDecorator;
use App\Modules\User\Entities\User;
use App\Modules\User\Pipes\Admin\User\Create\GetPipe;
use App\Modules\User\Pipes\Admin\Profile\Update\UpdatePipe;
use App\Modules\User\Pipes\Admin\User\Update\ImagePipe;
use App\Modules\User\Data\Decorators\UserProfileUpdate as UserProfileUpdateDataDecorator;

/**
 * Обновления профиля пользователя.
 */
class UserProfileUpdateAction extends Action
{
    /**
     * @var UserProfileUpdate Данные для действия обновления профиля пользователя.
     */
    private UserProfileUpdate $data;

    /**
     * @param UserProfileUpdate $data Данные для действия обновления профиля пользователя.
     */
    public function __construct(UserProfileUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return User Вернет результаты исполнения.
     */
    public function run(): User
    {
        $decorator = new UserUpdateDecorator(UserProfileUpdateDataDecorator::from($this->data->toArray()));

        $decorator->setActions([
            UpdatePipe::class,
            ImagePipe::class,
            GetPipe::class
        ]);

        return $decorator->run();
    }
}
