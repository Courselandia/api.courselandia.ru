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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Access\Entities\AccessSocial;
use App\Modules\User\Repositories\User;
use Cache;
use Closure;
use ReflectionException;
use App\Modules\User\Entities\User as UserEntity;
use Util;

/**
 * Регистрация нового пользователя через социальные сети: Получение данных для авторизованного пользователя.
 */
class DataPipe implements Pipe
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
     * @param  Entity|AccessSocial  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|AccessSocial $entity, Closure $next): mixed
    {
        if ($entity->create && !isset($entity->user->password)) {
            $entity->password = UserEntity::generatePassword();
        } else {
            $query = new RepositoryQueryBuilder($entity->user->id);

            $cacheKey = Util::getKey('access', 'user', $query);

            $user = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->user->get($query);
                }
            );

            $entity->password = $user->password;
        }

        return $next($entity);
    }
}
