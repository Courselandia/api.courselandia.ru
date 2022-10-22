<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Entities\AccessSignUp;
use Cache;
use Closure;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessSocial;
use App\Models\Contracts\Pipe;
use App\Modules\User\Repositories\UserRole;
use App\Modules\User\Entities\UserRole as UserRoleEntity;
use App\Modules\User\Enums\Role;

/**
 * Регистрация нового пользователя: добавление роли для пользователя.
 */
class RolePipe implements Pipe
{
    /**
     * Репозиторий роли.
     *
     * @var UserRole
     */
    private UserRole $userRole;

    /**
     * Конструктор.
     *
     * @param  UserRole  $userRole  Репозиторий роли.
     */
    public function __construct(UserRole $userRole)
    {
        $this->userRole = $userRole;
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
            $userRoleEntity = new UserRoleEntity();
            $userRoleEntity->user_id = $entity->id;
            $userRoleEntity->name = Role::USER;

            $this->userRole->create($userRoleEntity);
            Cache::tags(['access', 'user'])->flush();
        }

        return $next($entity);
    }
}
