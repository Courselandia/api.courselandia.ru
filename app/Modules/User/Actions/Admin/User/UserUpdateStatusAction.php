<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;

/**
 * Обновление статуса пользователя.
 */
class UserUpdateStatusAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var string|int
     */
    private string|int $id;

    /**
     * Статус пользователя.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param string|int $id ID пользователя.
     * @param bool $status Статус пользователя.
     */
    public function __construct(string|int $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     */
    public function run(): UserEntity
    {
        $action = new UserGetAction($this->id);
        $user = $action->run();

        if ($user) {
            $user->status = $this->status;
            User::find($this->id)->update($user->toArray());

            Cache::tags(['user'])->flush();

            return $user;
        }

        throw new UserNotExistException(trans('user::actions.admin.userUpdateStatusAction.notExistUser'));
    }
}
