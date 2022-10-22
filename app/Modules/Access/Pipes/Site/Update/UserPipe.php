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
use App\Models\Rep\RepositoryQueryBuilder;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Entities\AccessUpdate;
use App\Modules\User\Repositories\User;
use ReflectionException;
use Util;

/**
 * Изменение информации о пользователе: обновляем данные о пользователе.
 */
class UserPipe implements Pipe
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
        $query = new RepositoryQueryBuilder();
        $query->setId($entity->id)
            ->setActive(true);

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            $user->first_name = $entity->first_name;
            $user->second_name = $entity->second_name;
            $user->phone = $entity->phone;
            $this->user->update($user->id, $user);
            Cache::tags(['access', 'user'])->flush();

            $entity->user = $user;

            return $next($entity);
        }

        throw new UserNotExistException(trans('access::pipes.site.update.userPipe.notExistUser'));
    }
}
