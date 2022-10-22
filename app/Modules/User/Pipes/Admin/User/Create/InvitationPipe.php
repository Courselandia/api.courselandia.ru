<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\User\Emails\Invitation;
use App\Modules\User\Entities\UserCreate;
use Closure;
use Mail;

/**
 * Создание пользователя: отправка приглашения.
 */
class InvitationPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserCreate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|UserCreate $entity, Closure $next): mixed
    {
        if ($entity->invitation) {
            Mail::to($entity->login)->queue(new Invitation());
        }

        return $next($entity);
    }
}
