<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;

/**
 * Обновление пароля.
 */
class UserPasswordAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): UserEntity
    {
        if ($this->id) {
            $action = app(UserGetAction::class);
            $action->id = $this->id;
            $user = $action->run();

            if($user) {
                $user->password = bcrypt($this->password);

                User::find($this->id)->update($user->toArray());
                $user->password = null;

                Cache::tags(['user'])->flush();

                return new UserEntity($user->toArray());
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userPasswordAction.notExistUser'));
    }
}
