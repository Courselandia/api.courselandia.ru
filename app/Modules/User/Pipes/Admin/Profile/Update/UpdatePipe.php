<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\Profile\Update;

use App\Modules\User\Data\Decorators\UserProfileUpdate;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Models\User;
use App\Models\Data;

/**
 * Обновление профиля: обновление пользователя.
 */
class UpdatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|UserUpdate|UserProfileUpdate $data Данные для декоратора обновления.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws UserNotExistException
     */
    public function handle(Data|UserUpdate|UserProfileUpdate $data, Closure $next): mixed
    {
        $action = new UserGetAction($data->id);
        $user = $action->run();

        if ($user) {
            $user = UserEntity::from([
                ...$user->toArray(),
                ...$data->toArray(),
            ]);

            User::find($data->id)->update($user->toArray());
            Cache::tags(['user'])->flush();

            return $next($data);
        }

        throw new UserNotExistException(trans('user::pipes.admin.user.updatePipe.notExistUser'));
    }
}
