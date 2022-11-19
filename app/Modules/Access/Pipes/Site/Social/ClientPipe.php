<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Actions\AccessApiClientAction;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\Entities\AccessSocial;
use Closure;
use ReflectionException;

/**
 * Регистрация нового пользователя через социальные сети: получение клиента.
 */
class ClientPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSocial  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws InvalidPasswordException|ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|AccessSocial $entity, Closure $next): mixed
    {
        try {
            $action = app(AccessApiClientAction::class);
            $action->login = $entity->login;
            $action->force = true;
            $client = $action->run();

            $action = app(AccessApiTokenAction::class);
            $action->secret = $client->secret;
            $token = $action->run();

            $action = app(AccessGateAction::class);
            $action->id = $client->user->id;

            $user = $action->run();

            $entity->create = false;
            $entity->user = $user;
            $entity->client = $client;
            $entity->token = $token;

        } catch (UserNotExistException $error) {
            $entity->create = true;
        }

        return $next($entity);
    }
}
