<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Verified;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessVerified;
use App\Modules\Access\Entities\AccessVerify;
use Closure;

/**
 * Верификация пользователя: Получение данных для верифицированного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  AccessVerify|Entity  $entity  Сущность для хранения данных.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(AccessVerify|Entity $entity, Closure $next): mixed
    {
        $accessVerified = new AccessVerified();
        $accessVerified->user = $entity->user;
        $accessVerified->accessToken = $entity->token->accessToken;
        $accessVerified->refreshToken = $entity->token->refreshToken;

        return $next($accessVerified);
    }
}
