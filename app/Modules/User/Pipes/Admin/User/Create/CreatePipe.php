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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Models\User;
use App\Models\Data;
use Cache;
use Closure;

/**
 * Создание пользователя: создание пользователя.
 */
class CreatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|UserCreate $data Данные для декоратора создания пользователя.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Data|UserCreate $data, Closure $next): mixed
    {
        $data->password = bcrypt($data->password);
        $user = User::create($data->toArray());
        Cache::tags(['user'])->flush();
        $data->id = $user->id;

        return $next($data);
    }
}
