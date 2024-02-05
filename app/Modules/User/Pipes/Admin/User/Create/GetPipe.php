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
use App\Models\Enums\CacheTime;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Data\Decorators\UserProfileUpdate;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use Closure;
use Util;

/**
 * Создание пользователя: получение пользователя.
 */
class GetPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|UserUpdate|UserCreate|UserProfileUpdate $data Данные для декоратора.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|UserUpdate|UserCreate|UserProfileUpdate $data, Closure $next): mixed
    {
        $id = $data->id;
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

                return $user ? UserEntity::from($user->toArray()) : null;
            }
        );

        $user->password = null;
        unset($user->image);
        $data->user = $user;

        return $next($data);
    }
}
