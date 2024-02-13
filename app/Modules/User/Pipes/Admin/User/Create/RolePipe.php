<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use Cache;
use Closure;
use Exception;
use App\Modules\User\Models\User;
use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Data\Decorators\UserUpdate;
use App\Modules\User\Entities\UserRole as UserRoleEntity;
use App\Modules\User\Models\UserRole;

/**
 * Создание пользователя: добавление ролей к пользователю.
 */
class RolePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|UserCreate|UserUpdate $data Данные для декоратора.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws Exception
     */
    public function handle(Data|UserCreate|UserUpdate $data, Closure $next): mixed
    {
        if ($data->role) {
            try {
                $userRoleEntity = new UserRoleEntity();
                $userRoleEntity->user_id = $data->id;
                $userRoleEntity->name = $data->role;

                UserRole::create($userRoleEntity->toArray());
                Cache::tags(['user'])->flush();
            } catch (Exception $error) {
                User::destroy($data->id);
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($data);
    }
}
