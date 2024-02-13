<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\Access\Data\Decorators\AccessSignIn;

/**
 * Авторизация пользователя: Получение данных о пользователя.
 */
class GatePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|AccessSignIn $data Данные.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Data|AccessSignIn $data, Closure $next): mixed
    {
        $action = new AccessGateAction($data->id);

        $data->user = $action->run();

        return $next($data);
    }
}
