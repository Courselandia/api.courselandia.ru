<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\Entity;

/**
 * Сущность для статистики новых пользователей.
 */
class UserAnalytics extends Entity
{
    /**
     * Дата.
     *
     * @var string|null
     */
    public ?string $date_group = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $amount = null;
}