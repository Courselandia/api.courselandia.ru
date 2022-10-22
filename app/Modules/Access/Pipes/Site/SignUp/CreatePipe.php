<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Entities\AccessSignUp;
use App\Modules\Access\Entities\AccessSocial;
use App\Modules\User\Repositories\User;
use Cache;
use Closure;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Регистрация нового пользователя: создание пользователя.
 */
class CreatePipe implements Pipe
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
     * @param  Entity|AccessSocial|AccessSignUp  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|AccessSocial|AccessSignUp $entity, Closure $next): mixed
    {
        if ($entity->create) {
            $user = new UserEntity();
            $user->login = $entity->login;
            $user->password = bcrypt($entity->password);
            $user->first_name = $entity->first_name;
            $user->second_name = $entity->second_name;
            $user->phone = $entity->phone;
            $user->status = true;

            $entity->id = $this->user->create($user);
            Cache::tags(['access', 'user'])->flush();
        } else {
            $entity->id = $entity->user->id;
        }

        return $next($entity);
    }
}
