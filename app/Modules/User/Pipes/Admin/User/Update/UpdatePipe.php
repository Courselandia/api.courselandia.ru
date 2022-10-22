<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Update;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\UserUpdate;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\User\Repositories\User;
use App\Models\Exceptions\UserNotExistException;
use ReflectionException;

/**
 * Обновление пользователя: обновление пользователя.
 */
class UpdatePipe implements Pipe
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

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
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserUpdate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function handle(Entity|UserUpdate $entity, Closure $next): mixed
    {
        $action = app(UserGetAction::class);
        $action->id = $entity->id;
        $user = $action->run();

        if ($user) {
            $user->login = $entity->login;
            $user->first_name = $entity->first_name;
            $user->second_name = $entity->second_name;
            $user->status = $entity->status;
            $user->phone = $entity->phone;
            $user->verified = $entity->verified;
            $user->two_factor = $entity->two_factor;
            $user->image = $entity->image;

            $this->user->update($entity->id, $user);
            Cache::tags(['user'])->flush();

            return $next($entity);
        }

        throw new UserNotExistException(trans('user::pipes.admin.user.updatePipe.notExistUser'));
    }
}
