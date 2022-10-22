<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Repositories\User;
use Cache;
use Closure;
use ReflectionException;
use Util;

/**
 * Создание пользователя: получение пользователя.
 */
class GetPipe implements Pipe
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
     * @param  Entity|UserCreate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|UserCreate $entity, Closure $next): mixed
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($entity->id)
            ->setRelations([
                'verification',
                'auths',
                'role',
            ]);

        $cacheKey = Util::getKey('user', $query);

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        $user->password = null;

        unset($user->image);

        return $next($user);
    }
}
