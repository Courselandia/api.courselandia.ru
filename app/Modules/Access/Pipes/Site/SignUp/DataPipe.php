<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessSignedUp;
use App\Modules\Access\Entities\AccessSignIn;
use App\Modules\Access\Entities\AccessSignUp;
use App\Modules\Access\Entities\AccessSocial;
use Closure;

/**
 * Регистрация нового пользователя: Получение данных для зарегистрированного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSignIn|AccessSocial|AccessSignUp  $entity  Сущность.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return AccessSignedUp Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|AccessSignIn|AccessSocial|AccessSignUp $entity, Closure $next): AccessSignedUp
    {
        $accessSignedUp = new AccessSignedUp();
        $accessSignedUp->user = $entity->user;
        $accessSignedUp->accessToken = $entity->token->accessToken;
        $accessSignedUp->refreshToken = $entity->token->refreshToken;

        return $next($accessSignedUp);
    }
}
