<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Social;

use Closure;
use Config;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessSocial;
use App\Models\Contracts\Pipe;
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
     * @param  Auth  $auth  Класс для работы с Firebase.
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSocial  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|AccessSocial $entity, Closure $next): mixed
    {
        if ($this->check($entity->uid)) {
            return $next($entity);
        }

        return false;
    }

    /**
     * Проверка возможности подобной авторизации.
     *
     * @param  string  $id  Кодификационный номер авторизации.
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
