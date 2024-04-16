<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Gate;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\Data\Actions\AccessApiToken as AccessApiTokenData;
use App\Modules\Access\Data\Decorators\AccessSocial;
use App\Modules\Access\Data\Decorators\AccessSignUp;
use App\Modules\Access\Data\Decorators\AccessVerify;
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
     * @param Data|AccessSocial|AccessSignUp|AccessVerify $data Данные.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws InvalidPasswordException|UserNotExistException|ReflectionException
     */
    public function handle(Data|AccessSocial|AccessSignUp|AccessVerify $data, Closure $next): mixed
    {
        $action = new AccessGateAction($data->id);
        $user = $action->run();

        $action = new AccessApiTokenAction(AccessApiTokenData::from([
            'login' => $user->login,
            'force' => true,
        ]));

        $token = $action->run();

        $data->user = $user;
        $data->token = $token;

        return $next($data);
    }
}
