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
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Repositories\User;
use Cache;
use ReflectionException;

/**
 * Обновление пароля.
 */
class UserPasswordAction extends Action
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

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
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): UserEntity
    {
        if ($this->id) {
            $action = app(UserGetAction::class);
            $action->id = $this->id;
            $user = $action->run();

            if($user) {
                $user->password = bcrypt($this->password);
                $this->user->update($this->id, $user);
                $user->password = null;

                Cache::tags(['user'])->flush();

                return $user;
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userPasswordAction.notExistUser'));
    }
}
