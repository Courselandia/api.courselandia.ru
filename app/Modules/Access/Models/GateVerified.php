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
 * Класс для определения проверен ли пользователь или нет.
 */
class GateVerified
{
    /**
     * Метод для определения доступа.
     *
     * @param  User  $user  Данные пользователя.
     * @param  bool  $verified  Если указать true то проверит что пользователь верифицирован, если false, то нет.
     *
     * @return bool Вернет результат проверки.
     * @throws ParameterInvalidException
     */
    public function check(User $user, bool $verified = true): bool
    {
        $action = app(AccessGateAction::class);
        $action->id = $user->id;
        $userEntity = $action->run();

        if ($userEntity) {
            if ($verified) {
                return $userEntity->verification->status === true;
            }

            return $userEntity->verification->status === false;
        }

        return false;
    }
}
