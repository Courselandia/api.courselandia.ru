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
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Models\UserVerification;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;
use Cache;
use Closure;
use App\Models\Exceptions\RecordNotExistException;
use ReflectionException;
use Util;

/**
 * Создание пользователя: верификация пользователя.
 */
class VerifyPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserCreate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws RecordNotExistException|ReflectionException
     */
    public function handle(Entity|UserCreate $entity, Closure $next): mixed
    {
        $id = $entity->id;
        $cacheKey = Util::getKey('user', 'verification', 'model', $id);

        $userVerification = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return UserVerification::where('user_id', $id)
                    ->first();
            }
        );

        if ($userVerification) {
            $userVerification->status = $entity->verified;
            $userVerification->update($userVerification->toArray());

            Cache::tags(['user'])->flush();
        } else {
            $userVerification = new UserVerificationEntity();
            $userVerification->user_id = $entity->id;
            $userVerification->code = UserVerificationEntity::generateCode($entity->id);
            $userVerification->status = $entity->verified;

            UserVerification::create($userVerification->toArray());
            Cache::tags(['user'])->flush();
        }

        return $next($entity);
    }
}
