<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Update;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Entities\AccessUpdate;
use App\Modules\User\Models\User;
use ReflectionException;
use Util;

/**
 * Изменение информации о пользователе: обновляем данные о пользователе.
 */
class UserPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessUpdate  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws ReflectionException
     */
    public function handle(Entity|AccessUpdate $entity, Closure $next): mixed
    {
        $id = $entity->id;
        $cacheKey = Util::getKey('access', 'user', 'model', $id, 'role', 'verification');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return User::where('id', $id)
                    ->with([
                        'role',
                        'verification'
                    ])
                    ->active()
                    ->first();
            }
        );

        if ($user) {
            $user->first_name = $entity->first_name;
            $user->second_name = $entity->second_name;
            $user->phone = $entity->phone;
            $user->update($user->toArray());
            Cache::tags(['access', 'user'])->flush();

            $entity->user = new UserEntity($user->toArray());

            return $next($entity);
        }

        throw new UserNotExistException(trans('access::pipes.site.update.userPipe.notExistUser'));
    }
}
