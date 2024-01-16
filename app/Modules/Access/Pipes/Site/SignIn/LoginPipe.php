<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use App\Models\Contracts\Pipe;
use App\Models\DTO;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\DTO\Actions\AccessApiToken;
use App\Modules\Access\DTO\Decorators\AccessSignIn;
use App\Modules\OAuth\VO\Token;
use Closure;
use ReflectionException;

/**
 * Авторизация пользователя: производим авторизацию и генерацию ключей.
 */
class LoginPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSignIn $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws ReflectionException
     */
    public function handle(DTO|AccessSignIn $data, Closure $next): mixed
    {
        $action = new AccessApiTokenAction(new AccessApiToken($data->login, $data->password, $data->remember));
        $token = $action->run();
        $data->token = new Token($token->accessToken, $token->refreshToken);
        $data->id = $token->user->id;
        $data->user = $token->user;

        return $next($data);
    }
}
