<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use Closure;
use App\Models\Contracts\Pipe;
use App\Models\DTO;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\DTO\Decorators\AccessSignIn;

/**
 * Авторизация пользователя: Получение данных о пользователя.
 */
class GatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSignIn $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(DTO|AccessSignIn $data, Closure $next): mixed
    {
        $action = new AccessGateAction($data->id);

        $data->user = $action->run();

        return $next($data);
    }
}
