<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\Profile\Update;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\UserUpdate;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Modules\User\Models\User;
use App\Models\Exceptions\UserNotExistException;

/**
 * Обновление профиля: обновление пользователя.
 */
class UpdatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserUpdate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     */
    public function handle(Entity|UserUpdate $entity, Closure $next): mixed
    {
        $action = app(UserGetAction::class);
        $action->id = $entity->id;
        $user = $action->run();

        if ($user) {
            $user->first_name = $entity->first_name;
            $user->second_name = $entity->second_name;
            $user->phone = $entity->phone;
            $user->image = $entity->image;

            User::find($entity->id)->update($user->toArray());
            Cache::tags(['user'])->flush();

            return $next($entity);
        }

        throw new UserNotExistException(trans('user::pipes.admin.user.updatePipe.notExistUser'));
    }
}
