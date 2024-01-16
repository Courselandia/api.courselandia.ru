<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\Data;
use Cache;
use Closure;
use App\Models\Entity;
use App\Modules\Access\Data\Decorators\AccessSocial;
use App\Modules\Access\Data\Decorators\AccessSignUp;
use App\Models\Contracts\Pipe;
use App\Modules\User\Models\UserRole;
use App\Modules\User\Enums\Role;

/**
 * Регистрация нового пользователя: добавление роли для пользователя.
 */
class RolePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|AccessSocial|AccessSignUp $data Данные.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|AccessSocial|AccessSignUp $data, Closure $next): mixed
    {
        if ($data->create) {
            UserRole::create([
                'user_id' => $data->id,
                'name' => Role::USER->value,
            ]);

            Cache::tags(['access', 'user'])->flush();
        }

        return $next($data);
    }
}
