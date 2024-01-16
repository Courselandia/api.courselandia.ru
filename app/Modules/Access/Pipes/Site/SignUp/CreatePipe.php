<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\DTO;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Modules\Access\DTO\Decorators\AccessSocial;
use App\Modules\Access\DTO\Decorators\AccessSignUp;
use App\Modules\User\Models\User;

/**
 * Регистрация нового пользователя: создание пользователя.
 */
class CreatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSocial|AccessSignUp $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(DTO|AccessSocial|AccessSignUp $data, Closure $next): mixed
    {
        if ($data->create) {
            $dataUser = [
                ...$data->toArray(),
                'password' => bcrypt($data->password),
                'status' => true,
            ];

            $user = User::create($dataUser);
            $data->id = $user->id;

            Cache::tags(['access', 'user'])->flush();
        } else {
            $data->id = $data->user->id;
        }

        return $next($data);
    }
}
