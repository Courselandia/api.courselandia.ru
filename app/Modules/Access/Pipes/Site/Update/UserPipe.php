<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Update;

use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Models\User;
use ReflectionException;
use Util;
use App\Modules\Access\Data\Decorators\AccessUpdate;

/**
 * Изменение информации о пользователе: обновляем данные о пользователе.
 */
class UserPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|AccessUpdate $data Данные для декоратора изменения информации о пользователе.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws ReflectionException
     */
    public function handle(Data|AccessUpdate $data, Closure $next): mixed
    {
        $id = $data->id;
        $cacheKey = Util::getKey('access', 'user', 'model', $id, 'role', 'verification');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return User::where('id', $id)
                    ->with([
                        'role',
                        'verification',
                    ])
                    ->active()
                    ->first();
            }
        );

        if ($user) {
            $user->first_name = $data->first_name;
            $user->second_name = $data->second_name;
            $user->phone = $data->phone;
            $user->update($user->toArray());
            Cache::tags(['access', 'user'])->flush();

            $data->user = UserEntity::from($user->toArray());

            return $next($data);
        }

        throw new UserNotExistException(trans('access::pipes.site.update.userPipe.notExistUser'));
    }
}
