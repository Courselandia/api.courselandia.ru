<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Modules\User\Entities\User as UserEntity;

/**
 * Сущность для авторизованного или зарегистрированного пользователя через соц. сети.
 */
class AccessSocial extends AccessSignedUp
{

}
