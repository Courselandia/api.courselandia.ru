<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessSignedIn;
use App\Modules\Access\Entities\AccessSignIn;
use Closure;

/**
 * Авторизация пользователя: Получение данных для авторизованного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSignIn  $entity  Сущность.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|AccessSignIn $entity, Closure $next): mixed
    {
        $accessSignedIn = new AccessSignedIn();
        $accessSignedIn->user = $entity->user;
        $accessSignedIn->accessToken = $entity->token->accessToken;
        $accessSignedIn->refreshToken = $entity->token->refreshToken;

        return $next($accessSignedIn);
    }
}
