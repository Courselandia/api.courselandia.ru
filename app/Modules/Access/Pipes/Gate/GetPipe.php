<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Gate;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\Entities\AccessSignUp;
use App\Modules\Access\Entities\AccessSocial;
use App\Modules\Access\Entities\AccessVerify;
use Closure;
use ReflectionException;

/**
 * Данные о доступе пользователя: получение.
 */
class GetPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSignUp|AccessSocial|AccessVerify  $entity  Сущность.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ReflectionException
     * @throws ParameterInvalidException
     */
    public function handle(Entity|AccessSignUp|AccessSocial|AccessVerify $entity, Closure $next): mixed
    {
        $action = app(AccessGateAction::class);
        $action->id = $entity->id;
        $user = $action->run();

        $action = app(AccessApiTokenAction::class);
        $action->login = $user->login;
        $action->force = true;
        $token = $action->run();

        $entity->user = $user;
        $entity->token = $token;

        return $next($entity);
    }
}
