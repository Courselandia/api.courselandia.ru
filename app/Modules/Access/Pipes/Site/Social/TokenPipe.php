<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use Closure;
use App\Models\DTO;
use App\Models\Contracts\Pipe;
use ReflectionException;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\DTO\Actions\AccessApiToken;
use App\Modules\Access\DTO\Decorators\AccessSocial;

/**
 * Регистрация нового пользователя через социальные сети: получение клиента.
 */
class TokenPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSocial $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws InvalidPasswordException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function handle(DTO|AccessSocial $data, Closure $next): mixed
    {
        try {
            $action = new AccessApiTokenAction(AccessApiToken::from([
                ...$data->toArray(),
                'force' => false,
            ]));
            $token = $action->run();

            $action = new AccessGateAction($token->user->id);
            $user = $action->run();

            $data->create = true;
            $data->user = $user;
            $data->token = $token;

            return $next($data);
        } catch (UserNotExistException) {
            $data->create = true;

            return $next($data);
        }
    }
}
