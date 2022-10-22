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
use App\Modules\User\Repositories\User;
use App\Models\Exceptions\RecordNotExistException;
use ReflectionException;

/**
 * Обновление статуса пользователя.
 */
class UserUpdateStatusAction extends Action
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

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
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): UserEntity
    {
        $action = app(UserGetAction::class);
        $action->id = $this->id;
        $user = $action->run();

        if ($user) {
            $user->status = $this->status;
            $this->user->update($this->id, $user);

            Cache::tags(['user'])->flush();

            return $user;
        }

        throw new UserNotExistException(trans('user::actions.admin.userUpdateStatusAction.notExistUser'));
    }
}
