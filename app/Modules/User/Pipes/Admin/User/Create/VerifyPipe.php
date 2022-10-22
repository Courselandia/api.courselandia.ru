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
use App\Modules\User\Repositories\UserVerification;
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
     * Репозиторий верификации пользователя.
     *
     * @var UserVerification
     */
    private UserVerification $userVerification;

    /**
     * Конструктор.
     *
     * @param  UserVerification  $userVerification  Репозиторий верификации пользователя.
     */
    public function __construct(UserVerification $userVerification)
    {
        $this->userVerification = $userVerification;
    }

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
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('user_id', $entity->id));

        $cacheKey = Util::getKey('user', 'verification', $query);

        $userVerification = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->userVerification->get($query);
            }
        );

        if ($userVerification) {
            $userVerification->status = $entity->verified;
            $this->userVerification->update($userVerification->id, $userVerification);
            Cache::tags(['user'])->flush();
        } else {
            $userVerification = new UserVerificationEntity();
            $userVerification->user_id = $entity->id;
            $userVerification->code = UserVerificationEntity::generateCode($entity->id);
            $userVerification->status = $entity->verified;

            $this->userVerification->create($userVerification);
            Cache::tags(['user'])->flush();
        }

        return $next($entity);
    }
}
