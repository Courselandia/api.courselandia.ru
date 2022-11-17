<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Modules\Access\Entities\AccessSocial;
use App\Modules\User\Models\User;
use Cache;
use Closure;
use App\Modules\User\Entities\User as UserEntity;
use Util;

/**
 * Регистрация нового пользователя через социальные сети: Получение данных для авторизованного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSocial  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|AccessSocial $entity, Closure $next): mixed
    {
        if ($entity->create && !isset($entity->user->password)) {
            $entity->password = UserEntity::generatePassword();
        } else {
            $id = $entity->user->id;
            $cacheKey = Util::getKey('access', 'user', 'model', $id);

            $user = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($id) {
                    return User::find($id);
                }
            );

            $entity->password = $user->password;
        }

        return $next($entity);
    }
}
