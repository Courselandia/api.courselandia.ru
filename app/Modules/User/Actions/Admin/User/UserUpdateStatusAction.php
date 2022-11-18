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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Статус пользователя.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): UserEntity
    {
        $action = app(UserGetAction::class);
        $action->id = $this->id;
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
