<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
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
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Пароль.
     *
     * @var string
     */
    private string $password;

    /**
     * @param int|string $id ID пользователя.
     * @param string $password Пароль.
     */
    public function __construct(int|string $id, string $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     */
    public function run(): UserEntity
    {
        if ($this->id) {
            $action = new UserGetAction($this->id);
            $user = $action->run();

            if ($user) {
                $user->password = bcrypt($this->password);

                User::find($this->id)->update($user->toArray());
                $user->password = null;

                Cache::tags(['user'])->flush();

                return $user;
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userPasswordAction.notExistUser'));
    }
}
