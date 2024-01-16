<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Gate;

use App\Models\Contracts\Pipe;
use App\Models\DTO;
use App\Models\Entity;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\DTO\Actions\AccessApiToken as AccessApiTokenDto;
use App\Modules\Access\DTO\Decorators\AccessSocial;
use App\Modules\Access\DTO\Decorators\AccessSignUp;
use App\Modules\Access\DTO\Decorators\AccessVerify;
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
     * @param Entity|AccessSocial|AccessSignUp|AccessVerify $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws InvalidPasswordException|ParameterInvalidException|UserNotExistException|ReflectionException
     */
    public function handle(DTO|AccessSocial|AccessSignUp|AccessVerify $data, Closure $next): mixed
    {
        $action = new AccessGateAction($data->id);
        $user = $action->run();

        $action = new AccessApiTokenAction(AccessApiTokenDto::from([
            'login' => $user->login,
            'force' => true,
        ]));

        $token = $action->run();

        $data->user = $user;
        $data->token = $token;

        return $next($data);
    }
}
