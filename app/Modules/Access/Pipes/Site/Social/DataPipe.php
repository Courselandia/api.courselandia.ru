<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use App\Models\DTO;
use Cache;
use Closure;
use Util;
use App\Models\Contracts\Pipe;
use App\Models\Enums\CacheTime;
use App\Modules\User\Models\User;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\Access\DTO\Decorators\AccessSocial;

/**
 * Регистрация нового пользователя через социальные сети: Получение данных для авторизованного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSocial $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(DTO|AccessSocial $data, Closure $next): mixed
    {
        if ($data->create && !isset($data->user->password)) {
            $data->password = UserEntity::generatePassword();
        } else {
            $id = $data->user->id;
            $cacheKey = Util::getKey('access', 'user', 'model', $id);

            $user = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($id) {
                    return User::find($id);
                }
            );

            $data->password = $user->password;
        }

        return $next($data);
    }
}
