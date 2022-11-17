<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Verified;

use App\Models\Enums\CacheTime;
use Cache;
use Closure;
use Config;
use ReflectionException;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Access\Entities\AccessVerify;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserVerification;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\UserVerifiedException;
use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\RecordNotExistException;
use Util;

/**
 * Верификация пользователя: проверяем пользователя.
 */
class CheckPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  AccessVerify|Entity  $entity  Сущность для хранения данных.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws InvalidCodeException
     * @throws ParameterInvalidException
     * @throws ReflectionException|UserVerifiedException
     */
    public function handle(AccessVerify|Entity $entity, Closure $next): mixed
    {
        $id = $entity->id;
        $cacheKey = Util::getKey('access', 'user', 'model', $id);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return User::where('id', $id)
                    ->active()
                    ->first();
            }
        );

        if ($user) {
            $cacheKey = Util::getKey('access', 'userVerification', 'model', $id);

            $verification = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($id) {
                    return UserVerification::where('user_id', $id)->first();
                }
            );

            if ($verification) {
                if (Config::get('app.env') === 'testing' || Config::get('app.env') === 'local' || $verification->code === $entity->code) {
                    if ((bool)$verification->status === false) {
                        $verification->status = true;

                        $verification->update($verification->toArray());

                        return $next($entity);
                    }

                    throw new UserVerifiedException(trans('access::pipes.site.verified.checkPipe.userIsVerified'));
                }

                throw new InvalidCodeException(trans('access::pipes.site.verified.checkPipe.notCorrectCode'));
            }

            throw new RecordNotExistException(trans('access::pipes.site.verified.checkPipe.notExistCode'));
        }

        throw new UserNotExistException(trans('access::pipes.site.verified.checkPipe.notExistUser'));
    }
}
