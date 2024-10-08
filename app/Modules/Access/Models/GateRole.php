<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Models;

use App\Modules\Access\Actions\AccessGateAction;
use App\Modules\User\Models\User;

/**
 * Класс для определения доступа к страницам сайта через роль.
 */
class GateRole
{
    /**
     * Метод для определения доступа.
     *
     * @param User $user Данные пользователя.
     * @param string $nameRoles Название роли.
     *
     * @return bool Вернет результат проверки.
     */
    public function check(User $user, string $nameRoles): bool
    {
        $nameRoles = explode(':', $nameRoles);

        $action = new AccessGateAction($user->id);
        $userEntity = $action->run();

        if ($userEntity) {
            for ($y = 0; $y < count($nameRoles); $y++) {
                if ($userEntity->role->name->value === $nameRoles[$y]) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}
