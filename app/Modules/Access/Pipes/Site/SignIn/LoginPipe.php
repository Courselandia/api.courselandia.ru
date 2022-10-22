<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiClientAction;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Entities\AccessSignIn;
use App\Modules\OAuth\Entities\Token;
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
     * @param  Entity|AccessSignIn  $entity  Сущность.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws ReflectionException
     */
    public function handle(Entity|AccessSignIn $entity, Closure $next): mixed
    {
        $action = app(AccessApiClientAction::class);
        $action->login = $entity->login;
        $action->password = $entity->password;
        $action->remember = $entity->remember;

        $entity->client = $action->run();

        $action = app(AccessApiTokenAction::class);
        $action->secret = $entity->client->secret;

        $token = $action->run();

        $entity->id = $entity->client->user->id;
        $entity->token = new Token();
        $entity->token->secret = $entity->client->secret;
        $entity->token->accessToken = $token->accessToken;
        $entity->token->refreshToken = $token->refreshToken;

        return $next($entity);
    }
}
