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
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Entities\UserCreate;
use App\Modules\User\Repositories\UserRole;
use App\Modules\User\Entities\UserRole as UserRoleEntity;

/**
 * Создание пользователя: добавление ролей к пользователю.
 */
class RolePipe implements Pipe
{
    /**
     * Репозиторий ролей пользователя.
     *
     * @var UserRole
     */
    private UserRole $userRole;

    /**
     * Конструктор.
     *
     * @param  UserRole  $userRole  Репозиторий пользователей.
     */
    public function __construct(UserRole $userRole)
    {
        $this->userRole = $userRole;
    }

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserCreate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws Exception
     */
    public function handle(Entity|UserCreate $entity, Closure $next): mixed
    {
        if ($entity->role) {
            try {
                $userRoleEntity = new UserRoleEntity();
                $userRoleEntity->user_id = $entity->id;
                $userRoleEntity->name = $entity->role;

                $this->userRole->create($userRoleEntity);
                Cache::tags(['user'])->flush();
            } catch (Exception $error) {
                $this->userRole->destroy($entity->role);
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($entity);
    }
}
