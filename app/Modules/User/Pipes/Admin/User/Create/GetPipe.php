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
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Models\User;
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

        $id = $entity->id;
        $cacheKey = Util::getKey('user', $id, 'verification', 'auths', 'role');

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                $user = User::where('id', $id)
                    ->with([
                        'verification',
                        'auths',
                        'role',
                    ])->first();

                return $user ? new UserEntity($user->toArray()) : null;
            }
        );

        $user->password = null;
        unset($user->image);

        return $next($user);
    }
}
