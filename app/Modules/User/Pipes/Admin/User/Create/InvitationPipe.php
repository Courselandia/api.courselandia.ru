<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Emails\Invitation;
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
     * @param Data|UserCreate $data Данные для декоратора создания пользователя.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|UserCreate $data, Closure $next): mixed
    {
        if ($data->invitation) {
            Mail::to($data->login)->queue(new Invitation());
        }

        return $next($data);
    }
}
