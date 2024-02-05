<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;
use App\Modules\User\Models\UserVerification;
use Cache;
use Closure;
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
     * @param Entity|UserCreate|UserUpdate $data Данные для декоратора.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws RecordNotExistException|ReflectionException
     */
    public function handle(Data|UserCreate|UserUpdate $data, Closure $next): mixed
    {
        $id = $data->id;
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
            $userVerification->status = $data->verified;
            $userVerification->update($userVerification->toArray());

        } else {
            $userVerification = new UserVerificationEntity();
            $userVerification->user_id = $data->id;
            $userVerification->code = UserVerificationEntity::generateCode($data->id);
            $userVerification->status = $data->verified;

            UserVerification::create($userVerification->toArray());
        }

        Cache::tags(['user'])->flush();

        return $next($data);
    }
}
