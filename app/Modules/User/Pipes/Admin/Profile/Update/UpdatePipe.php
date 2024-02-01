<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\Profile\Update;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Models\User;
use Cache;
use Closure;
use App\Modules\User\Data\Decorators\UserProfileUpdate;
use App\Models\Data;

/**
 * Обновление профиля: обновление пользователя.
 */
class UpdatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|UserProfileUpdate $data Данные для декоратора обновления профиля пользователя.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws UserNotExistException
     */
    public function handle(Data|UserProfileUpdate $data, Closure $next): mixed
    {
        $action = new UserGetAction($data->id);
        $user = $action->run();

        if ($user) {
            $user->first_name = $data->first_name;
            $user->second_name = $data->second_name;
            $user->phone = $data->phone;
            $user->image = $data->image;

            User::find($data->id)->update($user->toArray());
            Cache::tags(['user'])->flush();

            return $next($data);
        }

        throw new UserNotExistException(trans('user::pipes.admin.user.updatePipe.notExistUser'));
    }
}
