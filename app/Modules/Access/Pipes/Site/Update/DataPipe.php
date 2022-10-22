<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\Update;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessUpdate;
use App\Modules\User\Entities\User;
use Closure;

/**
 * Изменение информации о пользователе: Получение данных для верифицированного пользователя.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessUpdate  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return User Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|AccessUpdate $entity, Closure $next): User
    {
        $entity->user->password = null;

        return $entity->user;
    }
}
