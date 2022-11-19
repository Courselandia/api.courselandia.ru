<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Models;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\User\Models\User;

/**
 * Класс для определения доступа к страницам сайта.
 */
class GateUser
{
    /**
     * Метод для определения доступа.
     *
     * @param  User  $user  Данные пользователя.
     *
     * @return bool Вернет результат проверки.
     * @throws ParameterInvalidException
     */
    public function check(User $user): bool
    {
        $action = app(AccessGateAction::class);
        $action->id = $user->id;
        $user = $action->run();

        return (bool)$user;
    }
}
