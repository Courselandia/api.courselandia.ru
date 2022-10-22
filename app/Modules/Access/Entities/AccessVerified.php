<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\OAuth\Entities\Token;
use App\Modules\User\Entities\User;

/**
 * Сущность для авторизации пользователя.
 */
class AccessVerified extends AccessSignedIn
{

}