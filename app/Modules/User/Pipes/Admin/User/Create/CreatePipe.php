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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Models\User;
use Cache;
use Closure;

/**
 * Создание пользователя: создание пользователя.
 */
class CreatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserCreate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|UserCreate $entity, Closure $next): mixed
    {
        $entity->password = bcrypt($entity->password);
        $user = User::create($entity->toArray());
        Cache::tags(['user'])->flush();
        $entity->id = $user->id;

        return $next($entity);
    }
}
