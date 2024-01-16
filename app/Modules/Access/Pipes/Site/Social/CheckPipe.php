<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use App\Models\Contracts\Pipe;
use App\Models\DTO;
use App\Modules\Access\DTO\Decorators\AccessSocial;
use Closure;
use Config;
use Kreait\Firebase\Contract\Auth;

/**
 * Авторизация через социальные сети: проверка входа через соц. сеть.
 */
class CheckPipe implements Pipe
{
    /**
     * Класс для работы с Firebase.
     *
     * @var Auth
     */
    private Auth $auth;

    /**
     * Конструктор.
     *
     * @param Auth $auth Класс для работы с Firebase.
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO|AccessSocial $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(DTO|AccessSocial $data, Closure $next): mixed
    {
        if ($this->check($data->uid)) {
            return $next($data);
        }

        return false;
    }

    /**
     * Проверка возможности подобной авторизации.
     *
     * @param string $id Кодификационный номер авторизации.
     *
     * @return mixed Вернет успешность проверки.
     */
    protected function check(string $id): bool
    {
        if (Config::get('app.env') == 'testing' || Config::get('app.env') == 'local') {
            return true;
        }

        return $this->auth->verifyIdToken($id);
    }
}
